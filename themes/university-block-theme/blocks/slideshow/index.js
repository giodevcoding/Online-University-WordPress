import { SlideshowEdit as edit } from "./edit";
import { Slideshow as save } from "./save";
import metadata from './block.json'

import "./editor.scss"
import "./render.scss"

const { name } = metadata;

const settings = {
    apiVersion: 2,
    icon: "slides",
    attributes: {
        align: { type: "string", default: "full" }
    },
    edit,
    save
}

wp.blocks.registerBlockType( { name, ...metadata }, settings);
