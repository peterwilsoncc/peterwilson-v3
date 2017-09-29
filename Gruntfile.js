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

		sass: {
			theme: {
				options: {
					indentType: 'tab',
					indentWidth: 1,
					outputStyle: 'expanded',
					sourceMap: true
				},
				files: {
					'content/themes/peter-wilson-2017/assets/css/theme.css' : 'content/themes/peter-wilson-2017/assets/css/theme.scss'
				}
			}
		}
	} );

	// Register tasks.
	grunt.registerMultiTask( 'phpunit', 'Runs PHPUnit tests.', commonTaskCallback );

	grunt.registerMultiTask( 'phplint', 'Runs PHP code sniffs.', commonTaskCallback );

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
	grunt.loadNpmTasks( 'grunt-sass' );

	grunt.registerTask( 'build:css', [ 'sass' ] );

	grunt.registerTask( 'build', [
		'build:css'
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
