import {useBlockProps} from "@wordpress/block-editor";
import {useSelect} from "@wordpress/data";
import {useState, useEffect} from 'react';
import apiFetch from "@wordpress/api-fetch";

const __ = wp.i18n.__;

export default function FeaturedProfessorEdit(props) {
    const [preview, setPreview] = useState("");

    const blockProps = useBlockProps({
        className: "professor-select-wrapper",
    });

    useEffect(() => {
        if (props.attributes.profID) {
            updateMeta();
            async function go() {
                
                const response = await apiFetch({
                    path: `/featured-professor/v1/get-HTML?profId=${props.attributes.profID}`,
                    method: "GET"
                });

                setPreview(response);
            }
            go();
        }

    }, [props.attributes.profID]);

    useEffect(() => {
        // Cleanup Function
        return () => {
            updateMeta();
        }
    }, []);


    function updateMeta() {
        const profsForMeta = wp.data.select("core/block-editor")
            .getBlocks()
            .filter(x => x.name == "paying-attention/featured-professor")
            .map(x => x.attributes.profID)
            .filter((x, index, arr) => {
                return arr.indexOf(x) == index
            });

        wp.data.dispatch("core/editor").editPost({meta: {featuredprofessor: profsForMeta}})
    }
    const allProfs = useSelect(select => {
        return select("core").getEntityRecords("postType", "professor", {per_page: -1});
    });
    if (allProfs == undefined) return <p {...blockProps}>Loading...</p>

    return (
        <div {...blockProps}>
            <div className="professor-select-container">
                <select onChange={e => props.setAttributes({profID: e.target.value})}>
                    <option value="-1">{__("Select a professor...", "paying-attention")}</option>
                    {allProfs.map(prof => {
                        return <option value={prof.id} selected={props.attributes.profID == prof.id}>
                            {prof.title.rendered}
                        </option>
                    })}
                </select>
            </div>
            {props.attributes.profID >= 0 && 
                <div dangerouslySetInnerHTML={{__html: preview}}></div>
            }
        </div>
    )
}