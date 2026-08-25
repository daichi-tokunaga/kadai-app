// ==========================================================
//    定数
// ==========================================================
/**
 * スマホ画面サイズ
 */
const SP_BREAKPOINT = 926;

// ==========================================================
//    初期処理
// ==========================================================
showPcWarningIfNeeded();
setupConfirmForms();
setupFollowTabs();

// ==========================================================
//    画面サイズ
// ==========================================================

/**
 * 画面がスマホサイズであるか判定
 */
function isSp() {
    return window.innerWidth <= SP_BREAKPOINT;
}

/**
 * PC 幅の場合に案内を表示する。
 * 画面を作り直すのではなく上に重ねるだけなので、閉じれば操作を継続できる。
 */
function showPcWarningIfNeeded() {
    if (isSp()) {
        return;
    }

    const banner = document.createElement("div");
    banner.textContent =
        `このアプリはモバイル版専用です。開発ツール(F12)で画面の横幅を${SP_BREAKPOINT}px以下に設定してご覧ください。`;
    banner.style.cssText = [
        "position:fixed",
        "top:0",
        "left:0",
        "width:100%",
        "padding:8px",
        "background:#333",
        "color:#fff",
        "font-size:14px",
        "text-align:center",
        "z-index:1000",
        "cursor:pointer",
    ].join(";");
    banner.title = "クリックで閉じる";
    banner.addEventListener("click", () => banner.remove());

    document.body.appendChild(banner);
}

// ==========================================================
//    確認ダイアログ
// ==========================================================

/**
 * data-confirm を持つボタンで送信されたフォームに確認ダイアログを挟む。
 *
 * click ではなく submit を捕まえることで、
 * 「キャンセルしたのに送信されてしまう」状態を防ぐ。
 */
function setupConfirmForms() {
    document.querySelectorAll("form").forEach((form) => {
        form.addEventListener("submit", (event) => {
            const trigger = event.submitter || form.querySelector("[data-confirm]");
            const message = trigger && trigger.dataset.confirm;

            if (message && !window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
}

// ==========================================================
//    エラーメッセージ
// ==========================================================

/**
 * エラーメッセージ配列を指定要素に描画する
 *
 * @param {HTMLElement} container 表示先の要素
 * @param {string[]} messages 表示するメッセージ
 */
function renderErrors(container, messages) {
    if (!container) {
        return;
    }

    container.innerHTML = "";

    if (messages.length === 0) {
        return;
    }

    const list = document.createElement("ul");
    messages.forEach((message) => {
        const item = document.createElement("li");
        item.textContent = message;
        list.appendChild(item);
    });
    container.appendChild(list);
}

// ==========================================================
//    フォロー/フォロワータブ
// ==========================================================

/**
 * フォロー/フォロワーの表示切り替え。
 *
 * 初期表示はサーバー側で決まっているため、ここでは切り替えのみを行う。
 */
function setupFollowTabs() {
    const tabs = document.getElementById("follow-tabs");
    if (!tabs) {
        return;
    }

    tabs.querySelectorAll("[data-tab]").forEach((tab) => {
        tab.addEventListener("click", () => switchTab(tab.dataset.tab));
    });
}

/**
 * 指定されたタブを選択状態にする
 */
function switchTab(selectedTabName) {
    ["follow", "follower"].forEach((name) => {
        const isSelected = name === selectedTabName;
        document.getElementById(`${name}-tab`).classList.toggle("selected", isSelected);
        document.getElementById(`${name}-list`).classList.toggle("hidden", !isSelected);
    });
}
