/* eslint-disable */

module.exports = function ( grunt ) {
	var path = require( 'path' );
	var fs   = require( 'fs' );

	function commonTaskCallback() {
		grunt.util.spawn( {
			cmd: this.data.cmd,
			args: this.data.args,
			opts: { stdio: 'inherit' }
		}, this.async() );
	}

	// Project configuration.
	grunt.initConfig( {
		phpunit: {
			'default': {
				cmd: '/bin/sh',
				args: [ '.tests/.bin/phpunit.sh' ]
			}
		},

		buildjs: {
			'default': {
				cmd: 'yarn',
				args: [ 'build:js' ]
			}
		},

		phplint: {
			'default': {
				cmd: 'yarn',
				args: [ 'lint:php' ]
			}
		},

		jslint: {
			default: {
				cmd: 'yarn',
				args: [ 'lint:js' ]
			}
		},

		csslint: {
			default: {
				cmd: 'yarn',
				args: [ 'lint:css' ]
			}
		},

		scsslint: {
			default: {
				cmd: 'yarn',
				args: [ 'lint:scss' ]
			}
		},

		cssmin: {
			options: {
				level: {
					1: {
						specialComments: false
					},
					2: {
						mergeSemantically: true,
						restructureRules: true
					},
				},
				format: {
					breaks: { // controls where to insert breaks
						afterAtRule     : true, // controls if a line break comes after an at-rule; e.g. `@charset`; defaults to `false`
						afterBlockBegins: true, // controls if a line break comes after a block begins; e.g. `@media`; defaults to `false`
						afterBlockEnds  : true, // controls if a line break comes after a block ends, defaults to `false`
						afterComment    : true, // controls if a line break comes after a comment; defaults to `false`
						afterProperty   : true, // controls if a line break comes after a property; defaults to `false`
						afterRuleBegins : true, // controls if a line break comes after a rule begins; defaults to `false`
						afterRuleEnds   : true, // controls if a line break comes after a rule ends; defaults to `false`
						beforeBlockEnds : true, // controls if a line break comes before a block ends; defaults to `false`
						betweenSelectors: true // controls if a line break comes between selectors; defaults to `false`
					},
					indentBy: 0, // controls number of characters to indent with; defaults to `0`
					indentWith: 'space', // controls a character to indent with, can be `'space'` or `'tab'`; defaults to `'space'`
					spaces: { // controls where to insert spaces
						aroundSelectorRelation: false, // controls if spaces come around selector relations; e.g. `div > a`; defaults to `false`
						beforeBlockBegins: false, // controls if a space comes before a block begins; e.g. `.block {`; defaults to `false`
						beforeValue: false // controls if a space comes before a value; e.g. `width: 1rem`; defaults to `false`
					},
					wrapAt: false // controls maximum line length; defaults to `false`
				},
				sourceMap: true,
				mergeIntoShorthands: false,
				roundingPrecision: -1
			},
			theme: {
				files: [ {
					expand: true,
					cwd: 'content/themes/peter-wilson-2017/assets/dist/css',
					src: ['*.css', '!**/*.min.css'],
					dest: 'content/themes/peter-wilson-2017/assets/dist/css',
					ext: '.min.css'
				} ]
			}
		},

		sass: {
			theme: {
				options: {
					indentType: 'tab',
					indentWidth: 1,
					outputStyle: 'expanded',
					sourceMap: true
				},
				files: [ {
					expand: true,
					cwd: 'content/themes/peter-wilson-2017/assets/src/css',
					src: ['*.scss'],
					dest: 'content/themes/peter-wilson-2017/assets/dist/css',
					ext: '.css'
				} ]
			}
		},

		uglify: {
			theme: {
				options: {
					sourceMap: true,
				},
				files: [ {
					expand: true,
					cwd: 'content/themes/peter-wilson-2017/assets/dist/js',
					src: ['*.js', '!**/*.min.js'],
					dest: 'content/themes/peter-wilson-2017/assets/dist/js',
					ext: '.min.js'
				} ]
			}
		},

		watch: {
			script: {
				files: [ 'content/**/*.js', '!**/dist/**/*.js' ],
				tasks: [ 'buildjs' ]
			},
			style: {
				files: [ 'content/**/*.scss' ],
				tasks: [ 'sass' ]
			}
		}
	} );

	// Register tasks.
	grunt.registerMultiTask( 'phpunit', 'Runs PHPUnit tests.', commonTaskCallback );

	grunt.registerMultiTask( 'phplint', 'Runs PHP code sniffs.', commonTaskCallback );

	grunt.registerMultiTask( 'buildjs', 'Build JS.', commonTaskCallback );

	grunt.registerMultiTask( 'jslint', 'Lint JS files.', commonTaskCallback );

	grunt.registerMultiTask( 'csslint', 'Lint CSS files.', commonTaskCallback );

	grunt.registerMultiTask( 'scsslint', 'Lint SCSS files.', commonTaskCallback );

	grunt.registerTask( 'precommit', 'Runs test and build tasks in preparation for a commit', function () {
		var done = this.async();
		var map = {
			svn: 'svn status --ignore-externals',
			git: 'git status --short'
		};

		find( [
			__dirname + '/.svn',
			__dirname + '/.git',
			path.dirname( __dirname ) + '/.svn'
		] );

		function find( set ) {
			var dir;

			if ( set.length ) {
				fs.stat( dir = set.shift(), function ( error ) {
					error ? find( set ) : run( path.basename( dir ).substr( 1 ) );
				} );
			} else {
				grunt.fatal( 'This project is not under version control.' );
			}
		}

		function run( type ) {
			var command = map[ type ].split( ' ' );

			grunt.util.spawn( {
				cmd: command.shift(),
				args: command
				}, function ( error, result, code ) {
					var taskList = [];

					if ( code !== 0 ) {
						grunt.fatal( 'The `' + map[ type ] + '` command returned a non-zero exit code.', code );
					}

					// Callback for finding modified paths.
					function testPath( path ) {
						var regex = new RegExp( ' ' + path + '$', 'm' );
						return regex.test( result.stdout );
					}

					// Callback for finding modified files by extension.
					function testExtension( extension ) {
						var regex = new RegExp( '.' + extension + '$', 'm' );
						return regex.test( result.stdout );
					}

					if ( [ 'package.json', 'Gruntfile.js' ].some( testPath ) ) {
						grunt.log.writeln( 'Configuration files modified. Running `prerelease`.' );
						taskList.push( 'prerelease' );
					} else {
						if ( [ 'png', 'jpg', 'gif', 'jpeg' ].some( testExtension ) ) {
							grunt.log.writeln( 'Image files modified. Minifying.' );
							taskList.push( 'precommit:image' );
						}

						[ 'js', 'scss', 'css', 'php' ].forEach( function ( extension ) {
							if ( testExtension( extension ) ) {
								grunt.log.writeln( extension.toUpperCase() + ' files modified. ' + extension.toUpperCase() + ' tests will be run.' );
								taskList.push( 'precommit:' + extension );
							}
						} );
					}

					grunt.task.run( taskList );

					done();
				}
			);
		}
	} );

	// all the plugins that is needed for above tasks
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-sass' );

	grunt.registerTask( 'build:js', [ 'buildjs', 'uglify' ] );

	grunt.registerTask( 'build:css', [ 'sass', 'cssmin' ] );

	grunt.registerTask( 'build', [
		'build:js',
		'build:css',
	] );

	grunt.registerTask( 'precommit:js', [ 'jslint' ] );

	grunt.registerTask( 'precommit:css', [ 'csslint' ] );

	grunt.registerTask( 'precommit:scss', [ 'scsslint' ] );

	grunt.registerTask( 'precommit:image', [ ] );

	grunt.registerTask( 'precommit:php', [ 'phplint', 'phpunit' ] );

	grunt.registerTask( 'prerelease', [
		'precommit:js',
		'precommit:css',
		'precommit:scss',
		'precommit:image',
		'precommit:php'
	] );

};
