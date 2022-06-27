import { SlideEdit as edit } from "./edit";
import { Slide as save } from "./save";
import metadata from './block.json'

import "./editor.scss"
import "./render.scss"

const { name } = metadata;

const settings = {
    apiVersion: 2,
    icon: "slides",
    edit,
    save
}

wp.blocks.registerBlockType( { name, ...metadata }, settings);
