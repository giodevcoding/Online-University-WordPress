import { ToolbarGroup, ToolbarButton } from "@wordpress/components";
import { RichText, BlockControls, useBlockProps } from "@wordpress/block-editor";

export function GenericHeadingEdit(props) {
    function handleTextChange(change){
        props.setAttributes({ text: change });
    } 

    const blockProps = useBlockProps();
    return (
        <div {...blockProps}>
            <BlockControls>
                <ToolbarGroup>
                    <ToolbarButton 
                        isPressed={props.attributes.size == 'large'} 
                        onClick={() => props.setAttributes({ size: 'large' })}
                    >
                        Large

                    </ToolbarButton>
                    <ToolbarButton 
                        isPressed={props.attributes.size == 'medium'} 
                        onClick={() => props.setAttributes({ size: 'medium' })}
                    >
                        Medium

                    </ToolbarButton>
                    <ToolbarButton 
                        isPressed={props.attributes.size == 'small'} 
                        onClick={() => props.setAttributes({ size: 'small' })}
                    >
                        Small
                    
                    </ToolbarButton>
                </ToolbarGroup>
            </BlockControls>

            <RichText 
                tagName="h1" 
                className={`headline headline--${props.attributes.size}`} 
                value={props.attributes.text} 
                onChange={handleTextChange}
                allowedFormats={['core/bold', 'core/italic']}
            />
                
        </div>
    );

}