// -------------予約情報描画---------------


// 1. 指定した日付のデータを取得する関数
async function fetchReserveData(date) {
    if (!date) return [];
    try {
        const res = await fetch(`./request_do.php?date=${date}`);
        if (!res.ok) throw new Error('Network response was not ok');
        return await res.json();
    } catch (error) {
        console.error("取得エラー:", error);
        return [];
    }
}

// 2. 取得したデータを画面に描画する関数
function renderReserveList(data) {
    const listContainer = document.getElementById("reserveInfo");
    listContainer.innerHTML = ""; 

    data.forEach(item => {
        const liElm = document.createElement("li");
        const timeElm = document.createElement("p");
        const methodElm = document.createElement("p");
        const countElm = document.createElement("span");

        liElm.className = "col-3 card m-1 p-2 text-center";
        timeElm.textContent = item["time"];
        
        // 予約可能数（reserve_count）に応じた表示
        if (parseInt(item["reserve_count"]) > 0) {
            methodElm.textContent = "空 ";
            countElm.textContent = `(残り ${item["reserve_count"]}枠)`;
            countElm.className = "badge bg-success";
        } else {
            methodElm.textContent = "満 ";
            countElm.textContent = "×";
            countElm.className = "badge bg-danger";
        }

        methodElm.appendChild(countElm);
        liElm.appendChild(timeElm);
        liElm.appendChild(methodElm);
        listContainer.appendChild(liElm);
    });
}

// 3. 画面の更新処理をまとめた関数
async function updateDisplay(date) {
    const data = await fetchReserveData(date);
    renderReserveList(data);
}

// 4. メイン処理（初期化とイベント設定）
function init() {
    const dateSelect = document.getElementById("dateSelect");

    // 選択が変わった時の処理
    dateSelect.addEventListener("change", (e) => {
        updateDisplay(e.target.value);
    });

    // 初期表示（ページ読み込み時の選択値で一度実行）
    if (dateSelect.value) {
        updateDisplay(dateSelect.value);
    }
}

// 実行
document.addEventListener("DOMContentLoaded", init);

// ---------------------------------------




// -------------予約実行処理---------------

// 1 モーダルを開く・閉じる
const dialog= document.querySelector("dialog");
const modalClose =document.getElementById("modalClose");

modalClose,addEventListener("click",()=>{
    dialog.close();
})

// 2　モ‐ダル描画
function modalRender(){
    
}