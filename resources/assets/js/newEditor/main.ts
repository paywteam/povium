import { detect } from "detect-browser"
import { ErrorManager } from "./ErrorManager"
import PVMEditor from "./PVMEditor"

const browser = detect()
if (browser.name === "firefox") {
	console.warn("You need FireFox with version at least 69.")
	ErrorManager.showError("버전 69 이상의 파이어폭스가 팔요합니다.")
}

let pvme = new PVMEditor(document.querySelector("#post-editor"))