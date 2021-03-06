import { AT } from "./AvailableTypes"
import EditSession from "./EditSession"
import PVMNode from "./PVMNode"
import PVMImageNode from "./PVMImageNode"
import SelectionManager from "./SelectionManager"
import TypeChecker from "./TypeChecker"
import PVMRange from "./PVMRange"

export default class NodeManager {
	constructor() {}

	/**
	 * Insert child before the referenced child.
	 * If the referenced child is null, it appends child to last.
	 */
	public static insertChildBefore(insertingChild: PVMNode, refChild: PVMNode) {
		// If the refChild is null,
		// append it to the last.
		if (!refChild) {
			NodeManager.appendChild(insertingChild)
			return
		}

		let previousChild = refChild.previousSibling
		let refChildDOM = refChild.element
		let refChildIndex = this.getChildIndex(refChild)

		let newChildDOM = insertingChild.element

		// Adds a node to JS storage
		EditSession.nodeList.splice(refChildIndex, 0, insertingChild)
		if (EditSession.nodeList[refChildIndex - 1]) {
			EditSession.nodeList[refChildIndex - 1].nextSibling = insertingChild
			insertingChild.previousSibling = EditSession.nodeList[refChildIndex - 1]
		}
		refChild.previousSibling = insertingChild
		insertingChild.nextSibling = refChild

		// Updates view

		// Inserting node is a list item
		if (TypeChecker.isListItem(insertingChild.type)) {
			// Referenced node is a list item
			if (TypeChecker.isListItem(refChild.type)) {
				if (refChild.kind === insertingChild.kind) {
					if (
						previousChild &&
						previousChild.type === "li" &&
						previousChild.kind === refChild.kind
					) {
						this.mergeLists(
							previousChild.element.parentElement,
							refChild.element.parentElement
						)
					}
					refChild.element.parentElement.insertBefore(
						insertingChild.element,
						refChild.element
					)
				} else {
					let abc = this.splitListByItem(
						refChild.element.parentElement,
						refChild.element
					)

					if (previousChild && TypeChecker.isListItem(previousChild.type)) {
						if (previousChild.kind === insertingChild.kind) {
							previousChild.element.parentElement.insertBefore(
								insertingChild.element,
								previousChild.element.nextElementSibling
							)
						} else {
							let wrapper = document.createElement(insertingChild.kind)
							wrapper.appendChild(insertingChild.element)
							abc.parentElement.insertBefore(wrapper, abc)
						}
					} else {
						let wrapper = document.createElement(insertingChild.kind)
						wrapper.appendChild(insertingChild.element)
						abc.parentElement.insertBefore(wrapper, abc)
					}
				}
			} else {
				if (previousChild && TypeChecker.isListItem(previousChild.type)) {
					if (previousChild.kind === insertingChild.kind) {
						previousChild.element.parentElement.insertBefore(
							insertingChild.element,
							previousChild.element.nextElementSibling
						)
					} else {
						let wrapper = document.createElement(insertingChild.kind)
						wrapper.appendChild(insertingChild.element)
						refChild.element.parentElement.insertBefore(
							wrapper,
							refChild.element
						)
					}
				} else {
					let wrapper = document.createElement(insertingChild.kind)
					wrapper.appendChild(insertingChild.element)
					refChild.element.parentElement.insertBefore(wrapper, refChild.element)
				}
			}
		} else {
			// Inserting node is not a list item
			// Referenced node is a list item
			if (TypeChecker.isListItem(refChild.type)) {
				let abc = this.splitListByItem(
					refChild.element.parentElement,
					refChild.element
				)
				abc.parentElement.insertBefore(insertingChild.element, abc)
			} else {
				refChild.element.parentElement.insertBefore(
					insertingChild.element,
					refChild.element
				)
			}
		}
	}

	/**
	 * @param {PVMNode} child
	 */
	public static appendChild(child: PVMNode) {
		let nodeList = EditSession.nodeList
		if (nodeList[nodeList.length - 1]) {
			nodeList[nodeList.length - 1].nextSibling = child
			child.previousSibling = nodeList[nodeList.length - 1]
		}
		child.isConnected = true
		nodeList.push(child)

		// Render dom
		if (child.type === "li") {
			let wrapper = document.createElement(child.kind)
			wrapper.appendChild(child.element)
			EditSession.editorBody.appendChild(wrapper)
			if (
				child.previousSibling &&
				child.previousSibling.type === "li" &&
				child.previousSibling.kind === child.kind
			) {
				this.mergeLists(
					child.previousSibling.element.parentElement,
					child.element.parentElement
				)
			}
		} else {
			EditSession.editorBody.appendChild(child.element)
		}
	}

	/**
	 * Returns the node's index.
	 */
	public static getChildIndex(node: PVMNode): number {
		return EditSession.nodeList.findIndex(function(element: PVMNode) {
			return element.isSameAs(node)
		})
	}

	public static getNodeByID(id: number): PVMNode {
		return EditSession.nodeList.find(function(element) {
			return element.id === id
		})
	}

	public static getNodeByElement(elm: Element): PVMNode {
		return this.getNodeByID(this.getNodeID(elm))
	}

	public static getNodeByChild(target: Node|Element|EventTarget): PVMNode {
		if ((<Element> target).closest) {
			for (let i = 0; i < AT.topTags.length; i++) {
				if ((<Element> target).closest(AT.topTags[i])) {
					return this.getNodeByElement((<Element> target).closest(AT.topTags[i]))
				}
			}
		} else {
			let targetParent = (<Node> target).parentElement
			for (let i = 0; i < AT.topTags.length; i++) {
				if (targetParent.closest(AT.topTags[i])) {
					return this.getNodeByElement(targetParent.closest(AT.topTags[i]))
				}
			}
		}
	}

	public static getNodeID(element: Element): number {
		if (!element) {
			console.error("Could not find element.", element)
			return
		}
		let id = Number(element.getAttribute("data-ni"))
		if (!id) {
			// error handler
			console.error("Can't get nodeID")
			return null
		} else {
			return id
		}
	}

	public static setNodeID(element: HTMLElement, id: number | string) {
		element.setAttribute("data-ni", String(id))
	}

	public static createNode(
		type: string,
		options?: {
			nodeId?: number
			kind?: string
			html?: string
			url?: string
		}
	): PVMNode {
		type = type.toLowerCase()

		if (
			(type === "li" && options && !("kind" in options)) ||
			(type === "li" && !options)
		) {
			console.error(`"li" node must be created with parent tag.`)
			return
		}

		let newNode: PVMNode
		if (type === "image") {
			newNode = new PVMImageNode()
		} else {
			newNode = new PVMNode()
		}

		// Set an unique auto incremental node ID
		if (options && options.nodeId) {
			newNode.id = options.nodeId
		} else {
			newNode.id = ++EditSession.lastNodeID
		}

		newNode.type = type
		if (options && "kind" in options) {
			newNode.kind = options.kind
		}

		let dom

		if (AT.textOnly.includes(newNode.type)) {
			// Text nodes
			dom = document.createElement(newNode.type)
			dom.innerHTML = "<br>"
			if (options && "html" in options) {
				dom.innerHTML = options.html
				if (options.html === "") {
					dom.innerHTML = "<br>"
				}
			}
			// newNode.element = dom
			newNode.setElement(dom)
			newNode.textElement = dom
		} else if (newNode.type === "image") {
			let n = newNode as PVMImageNode

			// Image node
			dom = document.createElement("figure")
			dom.classList.add("image")
			dom.contentEditable = "false"

			let imgWrapper = document.createElement("div")
			imgWrapper.className = "image-wrapper"

			// dummy contenteditable div for image selection
			let selectionBreak = document.createElement("div")
			selectionBreak.contentEditable = "true"
			selectionBreak.className = "selection-break"
			selectionBreak.innerHTML = "<br>"
			imgWrapper.appendChild(selectionBreak)

			let imgDOM = document.createElement("img")
			imgWrapper.appendChild(imgDOM)

			let captionDOM = document.createElement("figcaption")
			captionDOM.contentEditable = "true"
			captionDOM.innerHTML = ""

			dom.appendChild(imgWrapper)
			dom.appendChild(captionDOM)
			// newNode.element = dom
			n.textElement = captionDOM
			n.setElement(dom)

			// Set image url(src)
			n.setUrl(options.url)

			// Set image size
			if (options.kind) {
				dom.classList.add(options.kind)
			}

			// Caption exists
			if (options.html && options.html.length > 0) {
				;(<PVMImageNode> newNode).setCaption(options.html)
			}
		}

		dom.setAttribute("data-ni", String(newNode.id))

		return newNode
	}

	public static getFirstChild(): PVMNode {
		return EditSession.nodeList[0]
	}

	public static getLastChild(): PVMNode {
		return EditSession.nodeList[EditSession.nodeList.length - 1]
	}

	/**
	 * Removes the node from the editor.
	 * @param {PVMNode} removingNode
	 * @param {object} recordData { beforeRange, afterRange }
	 */
	public static removeChild(removingNode: PVMNode) {
		let index = this.getChildIndex(removingNode)
		let previousNode = removingNode.previousSibling
		let nextNode = removingNode.nextSibling

		if (removingNode.previousSibling) {
			removingNode.previousSibling.nextSibling = removingNode.nextSibling
		}

		if (removingNode.nextSibling) {
			removingNode.nextSibling.previousSibling = removingNode.previousSibling
		}

		// Process the js object
		EditSession.nodeList[index].previousSibling = null
		EditSession.nodeList[index].nextSibling = null
		EditSession.nodeList.splice(index, 1)

		// Process the dom (view)
		if (removingNode.type === "li") {
			let list = removingNode.element.parentElement
			list.removeChild(removingNode.element)
			if (list.querySelectorAll("li").length === 0) {
				list.parentElement.removeChild(list)
			}
		} else {
			removingNode.element.parentElement.removeChild(removingNode.element)
		}

		// Process merging list item
		if (
			previousNode &&
			previousNode.type === "li" &&
			nextNode &&
			nextNode.type === "li" &&
			previousNode.kind === nextNode.kind
		) {
			this.mergeLists(previousNode.element.parentElement, nextNode.element.parentElement)
		}
	}

	public static splitNode(newNodeID: number = null) {
		let range
		let currentNode = SelectionManager.getCurrentNode()
		let currentRange = SelectionManager.getCurrentRange()

		let bugSolver = document.createTextNode(" ")
		currentNode.textElement.appendChild(bugSolver)

		let newRange = new PVMRange({
			startNode: currentNode,
			startOffset: currentRange.start.offset,
			endNode: currentNode,
			endOffset: currentNode.getTextContent().length
		})

		SelectionManager.setRange(newRange)

		range = window.getSelection().getRangeAt(0)

		let extractedContents = range.extractContents()

		let newNode = this.createNode(currentNode.type, {
			kind: currentNode.kind,
			nodeId: newNodeID
		})
		let n
		newNode.textElement.innerHTML = ""
		while ((n = extractedContents.firstChild)) {
			newNode.textElement.appendChild(n)
		}

		window.getSelection().removeAllRanges()

		newNode.textElement.innerHTML = newNode.textElement.innerHTML.replace(
			/ $/g,
			""
		)
		this.insertChildBefore(newNode, currentNode.nextSibling)

		return newNode
	}

	public static mergeNodes(node1: PVMNode, node2: PVMNode) {
		if (!node1 || !node2) {
			return
		}

		this.normalize(node1.textElement)
		this.normalize(node2.textElement)

		node1.textElement.innerHTML += node2.textElement.innerHTML
		// node1.textElement.normalize()
		this.normalize(node1.element)

		this.removeChild(node2)
	}

	/**
	 * Splits list from 0 ~ index, (index + 1) ~ last and returns new list elm.
	 */
	public static splitListByIndex(listElm: HTMLElement, index: number) {
		let itemCount = listElm.querySelectorAll("li").length
		if (index + 1 > itemCount) {
			console.error(
				"The given index " + index + " is bigger than the list size.",
				listElm
			)
		}
	}

	/**
	 * Splits list and returns the new generated list element.
	 * If the splitting target is the first child,
	 * then it does nothing and returns given list element.
	 */
	public static splitListByItem(listElm: HTMLElement, childItem: HTMLElement) {
		let newList = document.createElement(listElm.nodeName)
		let yesInsert = false

		let returnList = newList

		let items = listElm.querySelectorAll("li")
		for (let i = 0; i < items.length; i++) {
			if (items[i].isSameNode(childItem)) {
				yesInsert = true
				if (i === 0) {
					returnList = listElm
					break
				}
			}
			if (yesInsert) {
				newList.appendChild(items[i])
			}
		}
		if (newList.querySelectorAll("li").length > 0) {
			listElm.parentElement.insertBefore(newList, listElm.nextElementSibling)
		}

		return returnList
	}

	/**
	 * Merges list1 and list2 into list1.
	 */
	public static mergeLists(
		list1: HTMLElement,
		list2: HTMLElement,
		forceMerge: boolean = false
	) {
		if (list1.nodeName !== list2.nodeName && !forceMerge) {
			return
		}

		if (list1.isSameNode(list2)) {
			// Skip merging same nodes
			return
		}

		let node
		while (node = list2.firstElementChild) {
			list1.appendChild(node)
		}

		list2.parentNode.removeChild(list2)
	}

	/**
	 * Remove empty tags and add <br> tag if the node is text-empty.
	 */
	public static normalize(element: HTMLElement) {
		let currentRange = SelectionManager.getCurrentRange()
		while (1) {
			if (!element.innerHTML.match(/<\/(.*)><\1 ?.*?>/gi)) {
				break
			} else {
				element.innerHTML = element.innerHTML.replace(/<\/(.*)><\1 ?.*?>/gi, "")
			}
		}
		element.childNodes.forEach(child => {
			if (child.textContent.length === 0) {
				child.parentNode.removeChild(child)
			}
		})

		if (element.textContent.length === 0) {
			element.appendChild(document.createElement("br"))
		}

		SelectionManager.setRange(currentRange)
	}

	public static textNodesUnder(elm: HTMLElement) {
		let n,
			a = []
		let walk = document.createTreeWalker(elm, NodeFilter.SHOW_TEXT, null, false)
		while ((n = walk.nextNode())) {
			a.push(n)
		}
		return a
	}

	/**
	 * Get the first or last text node inside the HTMLElement
	 */
	public static getTextNodeInElementNode(
		node: HTMLElement,
		firstOrLast: string
	) {
		var travelNode: ChildNode = node
		var returnNode = null
		if (!node) {
			return null
		}

		if (firstOrLast === "first") {
			while (1) {
				if (travelNode === null) {
					break
				} else if (travelNode.nodeType === 3) {
					returnNode = travelNode
					break
				} else {
					travelNode = travelNode.firstChild
				}
			}
		} else if (firstOrLast === "last") {
			while (1) {
				if (travelNode === null) {
					break
				} else if (travelNode.nodeType === 3) {
					returnNode = travelNode
					break
				} else {
					travelNode = travelNode.lastChild
				}
			}
		} else {
			console.error("Second parameter must be 'first' or 'last'.")
		}

		return returnNode
	}

	public static transformNode(
		node: PVMNode,
		newType: string,
		newKind?: string
	) {
		// If the original node type and
		// the new type is same, do nothing.
		newType = newType.toLowerCase()
		if (node.type === newType && node.kind === newKind) return

		let oldElm = node.element

		let newElm = document.createElement(newType)

		let nextNode = node.nextSibling

		this.removeChild(node)

		this.copySoul(node.element, newElm)
		node.type = newType
		node.kind = newKind
		node.replaceElement(newElm)

		if (newType === "li") {
			if (newKind === "ol") {
				node.textElement.innerHTML = node.textElement.innerHTML.replace(
					/^(<.* ?.*?)?1. /,
					"$1"
				)
				node.fixEmptiness()
			} else if (newKind === "ul") {
				node.textElement.innerHTML = node.textElement.innerHTML.replace(
					/^(<.* ?.*?)?- /,
					"$1"
				)
				node.fixEmptiness()
			}
		} else if (newType === "code") {
			// node.textElement.innerHTML = node.textElement.textContent
			node.textElement.innerHTML = node.textElement.innerHTML.replace(
				/^(<.* ?.*?)?```/,
				"$1"
			)
			node.fixEmptiness()
		}

		this.insertChildBefore(node, nextNode)
	}

	/**
	 * Copy nodeID and attributes.
	 */
	public static copySoul(fromElm: HTMLElement, toElm: HTMLElement) {
		toElm.innerHTML = fromElm.innerHTML
		toElm.setAttribute("data-ni", String(this.getNodeID(fromElm)))
		if (AT.alignable.includes(toElm.nodeName) && fromElm.style.textAlign) {
			toElm.style.textAlign = fromElm.style.textAlign
		}
	}

	public static convertToJSON() {}
}
