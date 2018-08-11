( function( wp, blocks ) {
	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	Object.keys( blocks ).forEach( function( block_name ) {
		var block = blocks[block_name];
		var block_attributes = {};
		var block_inputs = [];
		Object.keys( block.fields ).forEach( function( field_name ) {
			var field = block.fields[field_name];
			switch ( field.control ) {
				case 'RichText' :
					var input = {
						'type'	: 'RichText',
						'name'  : field_name,
						'props' : {
							'tagName' : 'p',
						},
					};
					var attribute = {
						type: 'array',
						source: 'children',
						selector: 'p',
					};
				break;
				case 'MediaLibrary' :
					var input = {
						'type'	: 'RichText',
						'name'  : field_name,
						'props' : {
							'tagName' : 'p',
						},
					};
					var attribute = {
					 type: 'string',
					};
			}
			block_inputs.push(
				input
			);
			block_attributes[field_name] = attribute;
		} );
		registerBlockType( block_name, {
			title: block.title,
			category: block.category,
			attributes: block_attributes,
			edit: function( props ) {
				var ret = [];
				block_inputs.forEach( function( input ) {
					input.props['value'] = props.attributes[block_name],
					input.props['onChange'] = function( new_value ){
						var n = {};
						n[input.name] = new_value
						props.setAttributes( n );
					},
					ret.push( el(
						wp.editor[ input.type ],
						input.props
					) );
				} );
				var inspector = [];
				inspector.push( el(
					wp.editor.InspectorControls,
					null,
					[
						el(
							wp.components.PanelBody,
							null,
							[
								el(
									wp.components.PanelRow,
									null,
									[
										el(
											wp.components.TextareaControl,
											null
										)
									]
								)
							]
						)
					]
				) );
				return inspector;
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
