window.onload = function() {
    function onClassicSwitchClick() {
        document.cookie = "classicCookie=false;"
        location.href = location.href.replace("?noapp=true","");
    }

    var classicSwitch = document.createElement('div');

    classicSwitch.textContent = "Switch to mobile";
    classicSwitch.onclick = onClassicSwitchClick;
    classicSwitch.id = 'classicSwitch';
    classicSwitch.style.position = "fixed";
    classicSwitch.style.backgroundColor = "#218CC6";
    classicSwitch.style.color = "#FFF";
    classicSwitch.style.bottom = "2%";
    classicSwitch.style.right = "2%";
    classicSwitch.style.padding = "7px";
    classicSwitch.style.fontSize = "12px";
    
    document.body.insertAdjacentElement('beforeend', classicSwitch);
}