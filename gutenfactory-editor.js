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
					};
					var attribute = {
						type: field.data_type,
					};
				break;
				case 'MediaUpload' :
					field.props.type='image';
				  field.props.render = function( obj ) {
						return el(
							wp.components.Button,
							{
								onClick: obj.open,
								className: 'components-button button button-large button-two',
							},
							'Select Image'
						)
					};
					var input = {
						'type'	: wp.editor.MediaUpload,
						'name'  : field_name,
						'props' : field.props,
					};
					var attribute = {
					 type: field.data_type,
					};
				break;
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
					input.props.onSelect = function( new_value ) {
						var n = {};
						n[input.name] = new_value.id;
						props.setAttributes( n );
					}
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
					{ 'className' : 'gutenfactory-settings ' + 'gutenfactory_' + block.slug },
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
				if ( props.isSelected ) {
					return [ block_ret, inspector_ret ];
				} else {
					return [ render_ret, inspector_ret ];
				}
			},

			save: function( props ) {
				return null;
			}
		} );
	});
} ( window.wp, window.gutenfactory_blocks ) );
