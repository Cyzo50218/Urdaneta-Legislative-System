export function populateOptions(selectElement, data, placeholder) {
    if (!selectElement) return;
    selectElement.innerHTML = `<option value="">${placeholder}</option>`;
    data.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.code;
        opt.textContent = item.name;
        selectElement.appendChild(opt);
    });
}

export function resetDropdowns(keys, selects) {
    keys.forEach(key => {
        if (selects[key]) {
            selects[key].innerHTML = `<option value="">Select ${key}</option>`;
        }
    });
}