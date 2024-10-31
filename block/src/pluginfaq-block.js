const { registerBlockType } = wp.blocks;
const { Fragment } = wp.element;
const { InspectorControls, PanelColorSettings } = wp.blockEditor;
const { TextControl, PanelBody, PanelRow } = wp.components;
const { serverSideRender: ServerSideRender } = wp;

registerBlockType(
	'plugin-faq-parser/pluginfaq-block',
	{
		title: 'Plugin FAQ Parser',
		icon: 'editor-help',
		category: 'widgets',

		edit ( props ) {
			return [
			<Fragment>
				<ServerSideRender
					block = 'plugin-faq-parser/pluginfaq-block'
					attributes = { props.attributes }
				/>
				<TextControl
					label = { pluginfaq_text.slug }
					value = { props.attributes.slug }
					onChange = { ( value ) => props.setAttributes( { slug: value } ) }
				/>

				<InspectorControls>
				{}
					<TextControl
						label = { pluginfaq_text.slug }
						value = { props.attributes.slug }
						onChange = { ( value ) => props.setAttributes( { slug: value } ) }
					/>
					<PanelBody title = { pluginfaq_text.color } >
						<PanelBody title = { pluginfaq_text.question } initialOpen = { false }>
								<PanelColorSettings
									title = { pluginfaq_text.bdcolor }
									colorSettings = { [
										{
											value: props.attributes.bdline,
											onChange: ( colorValue ) => props.setAttributes( { bdline: colorValue } ),
											label: pluginfaq_text.bdcolor,
		 								}
									] }
								>
								</PanelColorSettings>
								<PanelColorSettings
									title = { pluginfaq_text.bgcolor }
									colorSettings = { [
										{
											value: props.attributes.bdback,
											onChange: ( colorValue ) => props.setAttributes( { bdback: colorValue } ),
											label: pluginfaq_text.bgcolor,
		 								}
									] }
								>
								</PanelColorSettings>
								<PanelColorSettings
									title = { pluginfaq_text.txcolor }
									colorSettings = { [
										{
											value: props.attributes.bdtext,
											onChange: ( colorValue ) => props.setAttributes( { bdtext: colorValue } ),
											label: pluginfaq_text.txcolor,
		 								}
									] }
								>
								</PanelColorSettings>
						</PanelBody>
						<PanelBody title = { pluginfaq_text.answer } initialOpen = { false }>
								<PanelColorSettings
									title = { pluginfaq_text.bgcolor }
									colorSettings = { [
										{
											value: props.attributes.back,
											onChange: ( colorValue ) => props.setAttributes( { back: colorValue } ),
											label: pluginfaq_text.bgcolor,
		 								}
									] }
								>
								</PanelColorSettings>
								<PanelColorSettings
									title = { pluginfaq_text.txcolor }
									colorSettings = { [
										{
											value: props.attributes.text,
											onChange: ( colorValue ) => props.setAttributes( { text: colorValue } ),
											label: pluginfaq_text.txcolor,
		 								}
									] }
								>
								</PanelColorSettings>
						</PanelBody>
					</PanelBody>
				</InspectorControls>
			</Fragment>
			];
		},

		save () {
			return null;
		},

	}
);
