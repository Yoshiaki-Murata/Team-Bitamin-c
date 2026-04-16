// モ‐ダル開く
const mReserveBtn = document.getElementById("mReserveBtn");
mReserveBtn.addEventListener("click",()=>{
    document.querySelector("dialog").showModal();
})
// モーダル閉じる
const closeModal =document.getElementById("closeModal");
closeModal.addEventListener("click",()=>{
    document.querySelector("dialog").close();
})


const modalDate= document.getElementById("modalDate");
modalDate.addEventListener("change",async(e)=>{
    console.log(e.target.value);

    const res = await fetch(`./index_api.php?date=${e.target.value}`)
    const reserveData=await res.json();
    renderModalTable(reserveData)
})


// モーダル描画関数
function renderModalTable(reserveData) {
    const tbody = document.querySelector("#modalTable tbody");
    tbody.innerHTML = ""; 

    reserveData.forEach(r => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td class="text-center">${r.date}</td>
            <td class="text-center">${r.time}</td>
            <td class="text-center">${r.name}</td>
            <td class="text-center">
            ${r.class_name}
            </td>
        `;
        tbody.appendChild(tr);
    });
}