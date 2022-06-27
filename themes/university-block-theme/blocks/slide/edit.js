import { InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } from "@wordpress/block-editor"
import { Button, PanelBody, PanelRow } from "@wordpress/components"
import apiFetch from "@wordpress/api-fetch"
import { useEffect } from "@wordpress/element"

export function SlideEdit(props) {

    useEffect(() => {
        if (props.attributes.themeImage) {
            props.setAttributes({ imageURL: `${slide.themeImagePath}${props.attributes.themeImage}`});
        }
    }, [])

    useEffect(() => {
        console.log("???")
        if (props.attributes.imageID) {
            async function go() {
                const response = await apiFetch({
                    path: `wp/v2/media/${props.attributes.imageID}`,
                    method: "GET"
                })
                props.setAttributes({ 
                    imageURL: response.media_details.sizes["page-banner"].source_url, 
                    themeImage: ""
                });
            }
            go();
        }
    }, [props.attributes.imageID]);

    function onFileSelect(x) {
        props.setAttributes({ imageID: x.id })
    }

    const ALLOWED_BLOCKS = ['online-university/generic-button', 'online-university/generic-heading']

    const blockProps = useBlockProps()
    return (
        <div {...blockProps}>

            <InspectorControls>
                <PanelBody title="Background" initialOpen={true}>
                    <PanelRow>
                        <MediaUploadCheck>
                            <MediaUpload onSelect={onFileSelect} value={props.attributes.imageID} render={({ open }) => {
                                return <Button onClick={open}>Choose Image</Button>
                            }}/>
                        </MediaUploadCheck>
                    </PanelRow>  
                </PanelBody>
            </InspectorControls>

            <div className="hero-slider__slide" style={{backgroundImage: `url('${props.attributes.imageURL}')`}}>
                <div className="hero-slider__interior container">
                    <div className="hero-slider__overlay t-center">
                        <InnerBlocks allowedBlocks={ ALLOWED_BLOCKS } />
                    </div>
                </div>
            </div>
        </div>
    );
}