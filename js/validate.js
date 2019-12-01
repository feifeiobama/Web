function changeToRed(label) {
    label.style.color = "red";
    setTimeout(function () {
        label.style.color = "black"
    }, 1000);
}

function testArea(id, fn) {
    const area = document.getElementById(id);
    const label = document.getElementById(id + "Label");
    if (fn(area.value)) {
        label.style.color = "black";
        return true;
    } else {
        changeToRed(label);
        return false;
    }
}

function validate() {
    let submitOK = true;

    submitOK = testArea("name", function (nameTxt) {
        return nameTxt.length >= 1 && nameTxt.length <= 20 && nameTxt.indexOf(",") === -1;
    }) && submitOK;

    submitOK = testArea("age", function (ageTxt) {
        if (!/^[0-9]+$/.test(ageTxt)) {
            return false;
        }
        return parseInt(ageTxt) >= 1 && parseInt(ageTxt) <= 120;
    }) && submitOK;

    submitOK = testArea("email", function (emailTxt) {
        return /^[-._A-Za-z0-9]+@[-._A-Za-z0-9]+$/.test(emailTxt) && emailTxt.length <= 60;
    }) && submitOK;

    return submitOK;
}

function validateID() {
    return testArea("id", function (idTxt) {
        return /^[0-9]+$/.test(idTxt);
    });
}

function validateParty() {
    let submitOK = true;

    submitOK = testArea("time", function (timeTxt) {
        return moment(timeTxt, 'YYYY/MM/DD HH:mm', true).isValid();
    }) && submitOK;

    submitOK = testArea("place", function (placeTxt) {
        return placeTxt.length >= 1 && placeTxt.length <= 60;
    }) && submitOK;

    submitOK = testArea("host", function (hostTxt) {
        return hostTxt.length >= 1 && hostTxt.length <= 20;
    }) && submitOK;

    return submitOK;
}

function validateNum() {
    return testArea("num", function (numTxt) {
        return /^[0-9]+$/.test(numTxt);
    });
}

function validateDate() {
    return testArea("date", function (dateTxt) {
        return moment(dateTxt, 'YYYY/MM/DD', true).isValid();
    });
}

function validateHost() {
    return testArea("host2", function (hostTxt) {
        return hostTxt.length >= 1 && hostTxt.length <= 20;
    });
}

