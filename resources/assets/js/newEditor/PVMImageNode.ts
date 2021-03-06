import PVMNode from "./PVMNode"
import SelectionManager from "./SelectionManager"
import PopTool from "./PopTool"
import EditSession from "./EditSession"
import EventManager from "./EventManager"
import { SelectionChangeEvent } from "./EventManager"

export default class PVMImageNode extends PVMNode {
	captionEnabled: boolean = false
	kind: string = "fit"
	url: string

	constructor() {
		super()
	}

	selectNode() {
		this.element.classList.remove("caption-focused")
		this.element.classList.add("node-selected")
	}

	attachEventListeners() {
		this.textElement.addEventListener("keydown", e => {
			setTimeout(() => {
				this.setCaption()
			}, 0)
		})
		this.element.addEventListener("click", e => {
			if (e.target instanceof Element) {
				if (e.target.querySelector("img")) {
					this.selectImage(e.target.querySelector("img"))
				}
			}
		})
		document.addEventListener("selectionChanged", (e: SelectionChangeEvent) => {
			let range = e.detail.currentRange
			if (range.collapsed && range.start.node.isSameAs(this)) {
				if (range.start.state === 5) {
					this.selectImage(range.start.offset)
				} else {
					this.focusCaption()
				}
			} else if (range.start.node.isSameAs(this) && range.end.node.isSameAs(this)) {
				this.focusCaption()
			} else {
				this.release()
			}
		})
	}

	release() {
		this.element.querySelectorAll(".is-selected").forEach(elm => {
			elm.classList.remove("is-selected")
		})
		this.element.classList.remove("node-selected")
		this.element.classList.remove("caption-focused")
		PopTool.hideImageTool()
	}

	selectImage(imgElm?: Element | number) {
		let imageWrapper: Element
		if (!imgElm) {
			imgElm = this.element.querySelector("img")
		}

		if (typeof(imgElm) === "number") {
			imgElm = this.element.querySelectorAll("img")[imgElm]
		} else {
			// Add classed to image-wrapper
			imageWrapper = imgElm.closest(".image-wrapper")
			imageWrapper.classList.add("is-selected")
		}

		// Add classes to dom
		this.element.classList.remove("caption-focused")
		this.element.classList.add("node-selected")

		// Set imageTool
		PopTool.showImageTool(this.element.querySelector(".image-wrapper"))

		// Add range
		let jsRange = document.createRange()
		let selectionBreak = imageWrapper.querySelector(".selection-break")
		jsRange.setStart(selectionBreak, 0)
		jsRange.setEnd(selectionBreak, 0)
		let sel = window.getSelection()
		sel.removeAllRanges()
		sel.addRange(jsRange)

		// If the caption is empty, set default value
		if (!this.captionEnabled) {
			this.setInnerHTML("이미지 설명")
		}
	}

	focusCaption() {
		this.release()
		this.element.classList.add("caption-focused")
		PopTool.hideImageTool()

		if (!this.captionEnabled) {
			this.setInnerHTML("")
		}
	}

	setCaption(str?: string) {
		let tc = this.textElement.textContent
		if (str) {
			tc = str
			this.textElement.innerHTML = tc
		}
		if (tc.length > 0) {
			this.captionEnabled = true
			this.element.classList.add("caption-enabled")
		} else {
			this.captionEnabled = false
			this.element.classList.remove("caption-enabled")
			this.focusCaption()
		}
	}

	setKind(kind: string) {
		this.element.classList.remove("fit")
		this.element.classList.remove("full")
		this.element.classList.remove("large")
		this.element.classList.remove("float-left")

		this.kind = kind
		this.element.classList.add(kind)

		PopTool.hideImageTool()

		setTimeout(() => {
			PopTool.showImageTool(this.element.querySelector(".image-wrapper"))
		}, 500)
	}

	setUrl(url: string) {
		// Verify url
		//
		this.url = url
		this.element.querySelector("img").src = url
	}
}