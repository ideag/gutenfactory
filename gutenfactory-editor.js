( function( wp, blocks ) {
	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	Object.keys( blocks ).forEach( function( block_name ) {
		var block = blocks[block_name];
		var block_attributes = {};
		Object.keys( block.fields ).forEach( function( field_name ) {
			var field = block.fields[field_name];
			switch ( field.control ) {
				case 'RichText' :
					var attribute = {
						type: 'array',
						source: 'children',
						selector: 'p',
					};
				break;
				case 'MediaLibrary' :
				   var attribute = {
						 type: 'string',
					 };
			}
			block_attributes[field_name] = attribute;
		} );
		registerBlockType( block_name, {
			title: block.title,
			category: block.category,
			attributes: block_attributes,
			edit: function( props ) {
				return wp.element.createElement(
	        'div',
	        null,
	        'boo'
    		);
			},

			save: function( props ) {
				return wp.element.createElement(
	        'div',
	        null,
	        'boo'
    		);
			}
		} );
	});
} ( window.wp, window.gutenfactory_blocks ) );
