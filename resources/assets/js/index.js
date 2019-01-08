require("../less/povium.style.less")

require("./globalscript")
require("./globalnav")
require("./home/homeController")
require("./profile/profile-home")
require("./login")
require("./register/register")
import Pjax from "pjax"
import topbar from "./topbar"

let pjax = new Pjax({
	elements: "a:not(.full-load)",
	selectors: [
		"title", "povium-app"
	],
	cacheBust: false
})
topbar.config({
	barThickness: 2,
	barColors: {
		"0": "#5f42ff",
		"1": "#5f42ff"
	},
	shadowColor: "rgba(0,0,0,0)"
})
document.addEventListener('pjax:send', topbar.show)
document.addEventListener('pjax:complete', topbar.hide)

window.onload = () => {
	setTimeout(() => {
		document.body.style.opacity = "1";
	}, 10);
}
