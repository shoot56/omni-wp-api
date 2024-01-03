function handleTabClick(event) {
	event.preventDefault();
	const targetTabId = event.target.getAttribute("href").substring(1);

	const tabs = document.querySelectorAll(".tab-content");
	tabs.forEach((tab) => {
		tab.style.display = "none";
	});

	const tabLinks = document.querySelectorAll(".nav-tab");
	tabLinks.forEach((tabLink) => {
		tabLink.classList.remove("nav-tab-active"); 
	});

	document.getElementById(targetTabId).style.display = "block";
	event.target.classList.add("nav-tab-active"); 

	localStorage.setItem("activeTab", targetTabId);
}

const tabs = document.querySelectorAll(".nav-tab");
tabs.forEach((tab) => {
	tab.addEventListener("click", handleTabClick);
});

const activeTab = localStorage.getItem("activeTab");
if (activeTab) {
	const tabLink = document.querySelector(`.nav-tab[href="#${activeTab}"]`);
	if (tabLink) {
		tabLink.click();
	}
} else {
	const firstTab = document.querySelector(".nav-tab");
	if (firstTab) {
		firstTab.click();
	}
}

