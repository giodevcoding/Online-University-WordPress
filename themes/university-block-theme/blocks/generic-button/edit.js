import { ToolbarGroup, ToolbarButton, Popover, Button, PanelBody, PanelRow, ColorPalette } from "@wordpress/components";
import { RichText, InspectorControls, BlockControls, __experimentalLinkControl as LinkControl, getColorObjectByColorValue, useBlockProps } from "@wordpress/block-editor";
import { link } from "@wordpress/icons";
import { useState } from "@wordpress/element"
import themeColors from "../../includes/theme-colors"

export function GenericButtonEdit(props) {
    const [isLinkPickerVisible, setIsLinkPickerVisible] = useState(false);
    const currentColorValue = themeColors.filter( color => {
        return color.name == props.attributes.colorName;
    })[0].color;
   

    function handleTextChange(change){
        props.setAttributes({ text: change });
    } 

    function buttonHandler() {
        setIsLinkPickerVisible(prev => !prev);
    }

    function handleColorChange(colorCode) {

        const { name } = getColorObjectByColorValue(themeColors, colorCode)
        props.setAttributes({ colorName: name });
    }

    function handleLinkChange(newLink) {
        console.log(newLink)
        props.setAttributes({ linkObject: newLink });
    }

    const blockProps = useBlockProps();
    return (
        <div {...blockProps}>
            <BlockControls>
                <ToolbarGroup>
                    <ToolbarButton onClick={buttonHandler} icon={link}></ToolbarButton>
                </ToolbarGroup>
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

            <InspectorControls>
                <PanelBody title="Color" initialOpen={true}>
                    <PanelRow>
                        <ColorPalette colors={themeColors} value={currentColorValue} onChange={handleColorChange} disableCustomColors clearable={false}/>
                    </PanelRow>
                </PanelBody>
            </InspectorControls>

            <RichText 
                tagName="a" 
                className={`btn btn--${props.attributes.size} btn--${props.attributes.colorName}`} 
                value={props.attributes.text} 
                onChange={handleTextChange}
            />

            {isLinkPickerVisible && (
                <Popover position="middle center" onFocusOutside={() => {
                    setIsLinkPickerVisible(false);
                    console.log("OUTSIDE")
                }}>
                    <LinkControl settings={[]} value={props.attributes.linkObject} onChange={handleLinkChange}/>
                    <Button variant="primary" onClick={() => {setIsLinkPickerVisible(false)}} style={{ display: "block", width: "100%"}}>Confirm Link</Button>
                </Popover>
            )}
                
        </div>
    );

}