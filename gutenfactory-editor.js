( function( wp, blocks ) {
	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	Object.keys( blocks ).forEach( function( block_name ) {
		var block 						= blocks[block_name];
		var block_attributes 	= {};
		var block_inputs 			= [];
		var inspector_inputs 	= [];
		Object.keys( block.fields ).forEach( function( field_name ) {
			var field = block.fields[ field_name ];
			switch ( field.control ) {
				case 'RichText' :
					var input = {
						'type'	: wp.editor.RichText,
						'name'  : field_name,
						'default' : field.default,
						'props' 	: field.props,

						'props' : {
							'format' : 'string',
							'tagName'	: 'div',
							'className' : 'content',
						},
					};
					var attribute = {
						type: 'string',
					};
				break;
				// case 'MediaUpload' :
				// 	var input = {
				// 		'type'	: wp.editor.MediaUpload,
				// 		'name'  : field_name,
				// 		'props' : {
				// 			'render' : function( open ) { return el( wp.components.Button, { onClick: open.open }, 'Upload' ) },
				// 		// 	'accept' : 'image/*',
				// 		},
				// 		// 'content' : 'Upload',
				// 	};
				// 	var attribute = {
				// 	 type: 'object',
				// 	};
				// break;
				default :
					var input = {
						'type'		: wp.components[ field.control ],
						'name'  	: field_name,
						'default' : field.default,
						'props' 	: field.props,
					};
					var attribute = {
					 type: field.data_type,
					};
				break;
			}
			switch( field.position ) {
				case 'inline' :
					block_inputs.push( input );
				break;
				case 'inspector' :
					inspector_inputs.push( input );
				break;
			}
			block_attributes[field_name] = attribute;
		} );
		console.log( block_attributes );
		registerBlockType( block_name, {
			title: block.title,
			category: block.category,
			attributes: block_attributes,
			edit: function( props ) {
				var setup_input = function ( input, props ) {
					input.props['value'] = undefined === props.attributes[ input.name ] ? input.default : props.attributes[ input.name ],
					input.props['onChange'] = function( new_value ){
						console.log( new_value );
						var n = {};
						n[input.name] = new_value;
						props.setAttributes( n );
					};
					console.log( input );
					return el(
						input.type,
						input.props,
						input.content,
					);
				}
				var block_ret = [];
				block_inputs.forEach( function( input ) {
					block_ret.push( setup_input( input, props ) );
				} );
				block_ret = el(
					'div',
					{ 'className' : 'gutenfactory-settings' },
					block_ret
				);
				var inspector = [];
				inspector_inputs.forEach( function( input ) {
					var inspector_el = setup_input( input, props );
					inspector.push(
						el(
						  wp.components.PanelRow,
						  null,
						  inspector_el
					  )
				  );
				} );
				var inspector_ret = [];
				inspector_ret.push( el(
					wp.editor.InspectorControls,
					null,
					el(
						wp.components.PanelBody,
						null,
						inspector
					)
				) );
				var render_ret = el(
					wp.components.ServerSideRender,
					{
						'block'				: block_name,
						'attributes' 	: props.attributes,
					}
				);
				return [block_ret, inspector_ret ];
			},

			save: function( props ) {
				return false;
			}
		} );
	});
} ( window.wp, window.gutenfactory_blocks ) );
