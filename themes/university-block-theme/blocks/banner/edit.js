import { InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } from "@wordpress/block-editor"
import { Button, PanelBody, PanelRow } from "@wordpress/components"
import apiFetch from "@wordpress/api-fetch"
import { useEffect } from "@wordpress/element"

export function BannerEdit(props) {

    //Set Default fallback Image (since banner.fallbackImage cannot be accessed in block.json)
    useEffect(() => {
        if (!props.attributes.imageURL) {
            props.setAttributes({ imageURL: banner.fallbackImage })
        }
    }, [])

    useEffect(() => {
        if (props.attributes.imageID) {
            async function go() {
                const response = await apiFetch({
                    path: `wp/v2/media/${props.attributes.imageID}`,
                    method: "GET"
                })
                props.setAttributes({ imageURL: response.media_details.sizes["page-banner"].source_url });
            }
            go();
        }
    }, [props.attributes.imageID]);

    function onFileSelect(x) {
        props.setAttributes({ imageID: x.id })
        console.log(props.attributes.imageURL);
    }

    const ALLOWED_BLOCKS = ['online-university/generic-button', 'online-university/generic-heading']

    const blockProps = useBlockProps({ className: "page-banner" })
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

            <div className="page-banner__bg-image" style={{backgroundImage: `url('${props.attributes.imageURL}')`}}></div>
            <div className="page-banner__content container t-center c-white">
                <InnerBlocks allowedBlocks={ ALLOWED_BLOCKS } />
            </div>
            
        </div>
    );
}