const FontFaceObserver = require( '../../../../../../node_modules/fontfaceobserver/fontfaceobserver.standalone' );

( function ( window ){
	window.PWCC = window.PWCC || {};
	const PWCC = window.PWCC;
	const document = window.document;

	const loadFontLibrary = function ( config ) {
		let allPromises = [];
		const html = document.documentElement;

		if ( config.htmlClass && sessionStorage[ config.htmlClass ] ) {
			html.classList.add( config.htmlClass );
		}

		for ( let i = 0; i < config.fonts.length; i++ ) {
			let font = new FontFaceObserver( config.fonts[i].name, config.fonts[i].config );
			allPromises.push( font.load() );
		}

		window.Promise.all( allPromises ).then( function () {
			html.classList.add( config.htmlClass );
			sessionStorage[ config.htmlClass ] = true;
		} );
	};

	const configSetOne = {
		htmlClass: 'wf-set-1',
		// eslint-disable-next-line key-spacing
		fonts: [
			{ name: 'Roboto', config: {}},
			{ name: 'Roboto Mono', config: {}},
			{ name: 'Roboto Slab', config: { weight: 700 }},
		],
	};

	loadFontLibrary( configSetOne );

	PWCC.domReady = new window.Promise( function ( resolve ) {
		function onReady() {
			resolve();
			document.removeEventListener( 'DOMContentLoaded', onReady, true );
			window.removeEventListener( 'load', onReady, true );
		}

		if ( document.readyState === 'complete' ) {
			resolve();
		} else {
			document.addEventListener( 'DOMContentLoaded', onReady, true );
			window.addEventListener( 'load', onReady, true );
		}
	} );

	PWCC.loadFontLibrary = loadFontLibrary;
} )( window );
