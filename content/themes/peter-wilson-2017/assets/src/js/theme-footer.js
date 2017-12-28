( function ( window ){
	window.PWCC = window.PWCC || {};
	const PWCC = window.PWCC;
	const document = window.document;

	PWCC.domReady.then( function () {
		const css = document.createElement( 'link' );

		css.type = 'text/css';
		css.rel = 'stylesheet';
		css.href = 'https://fonts.googleapis.com/css?family=Roboto+Mono:400i,700,700i|Roboto:400i,500,500i,700,700i';

		document.head.appendChild( css );

		const configSetTwo = {
			htmlClass: 'has-WebFonts-Stage2',
			/* eslint-disable key-spacing, indent */
			// @codingStandardsIgnoreStart
			// What a shit show start.
			fonts: [
				{
					name: 'Roboto',
					config: {
						weight: 400,
						style: 'italic',
					},
				},
				{
					name: 'Roboto',
					config: { weight: 500 },
				},
				{
					name: 'Roboto',
					config: {
						weight: 500,
						style: 'italic',
					},
				},
				{
					name: 'Roboto',
					config: { weight: 700 },
				},
				{
					name: 'Roboto',
					config: {
						weight: 700,
						style: 'italic',
					},
				},
				{
					name: 'Roboto Mono',
					config: {
						weight: 400,
						style: 'italic',
					},
				},
				{
					name: 'Roboto Mono',
					config: { weight: 700 },
				},
				{
					name: 'Roboto Mono',
					config: {
						weight: 700,
						style: 'italic',
					},
				},
			],
			// @codingStandardsIgnoreEnd
			/* eslint-enable key-spacing, indent */
		};

		PWCC.loadFontLibrary( configSetTwo );
	} );
} )( window );
