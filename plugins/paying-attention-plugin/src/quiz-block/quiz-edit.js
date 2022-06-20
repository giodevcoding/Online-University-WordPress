import {TextControl, Flex, FlexBlock, FlexItem, Button, Icon, PanelBody, PanelRow} from "@wordpress/components"
import {InspectorControls, BlockControls, AlignmentToolbar, useBlockProps} from "@wordpress/block-editor";
import {ChromePicker} from "react-color";

export default function QuizEdit(props) {

    const blockProps = useBlockProps({
        className: "paying-attention-edit-block",
        style: {backgroundColor: props.attributes.bgColor}
    });

    function updateQuestion(value) {
        props.setAttributes({question: value});
    }

    function deleteAnswer(indexToDelete) {
        if( indexToDelete == props.attributes.correctAnswer ){
            props.setAttributes({correctAnswer: undefined});
        }
        props.setAttributes({answers: props.attributes.answers.filter((_, index) => {return indexToDelete != index})});
    }

    function markAsCorrect(index) {
        props.setAttributes({correctAnswer: index})
    }

    return (
        <div {...blockProps} >
            <BlockControls>
                <AlignmentToolbar value={props.attributes.alignment} onChange={x => props.setAttributes({alignment: x})} />
            </BlockControls>
            <InspectorControls>
                <PanelBody title="Background Color">
                    <PanelRow>
                        <ChromePicker color={props.attributes.bgColor} onChangeComplete={color => props.setAttributes({bgColor: color.hex})} disableAlpha={true}/>
                    </PanelRow> 
                </PanelBody>    
            </InspectorControls>
            <TextControl className="paying-attention-question" label="Question:" value={props.attributes.question} onChange={updateQuestion} />
            <p className="answers-headline">Answers:</p>
            {props.attributes.answers.map((answer, index) => {
                return (
                    <Flex>
                        <FlexBlock>
                            <TextControl value={answer} onChange={(newValue) => {
                                const newAnswers = props.attributes.answers.concat([]);
                                newAnswers[index] = newValue;
                                props.setAttributes({answers: newAnswers})
                            }} autoFocus={answer == undefined}/>
                        </FlexBlock>
                        <FlexItem>
                            <Button onClick={() => markAsCorrect(index)}>
                                <Icon className="mark-as-correct" icon={props.attributes.correctAnswer == index ? "star-filled" : "star-empty"} />
                            </Button>
                        </FlexItem>
                        <FlexItem>
                            <Button className="attention-delete" variant="link" onClick={() => deleteAnswer(index)}>Delete</Button>
                        </FlexItem>
                    </Flex>
                )
            })}
            
            <Button variant="primary" onClick={() => {
                let answerAdded = props.attributes.answers.concat([undefined]);
                props.setAttributes({answers: answerAdded}) 
            }}>Add another answer</Button>
        </div>
    )
}