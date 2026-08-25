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
setupSignupValidation();
setupPostValidation();
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
//    バリデーション
// ==========================================================

/**
 * エラーメッセージ配列を指定要素に描画する
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

/**
 * 新規登録画面のバリデーション。
 *
 * ルール(正規表現・最小文字数)はサーバー側の SignupRequest から
 * data 属性経由で渡されるため、PHP 側と必ず同じ条件になる。
 */
function setupSignupValidation() {
    const form = document.getElementById("signup-form");
    if (!form) {
        return;
    }

    const asciiPattern = new RegExp(form.dataset.asciiPattern);
    const emailPattern = new RegExp(form.dataset.emailPattern);
    const passwordMinLength = Number(form.dataset.passwordMinLength);
    const errorArea = document.getElementById("signup-client-errors");

    form.addEventListener("submit", (event) => {
        const email = form.elements.email.value;
        const name = form.elements.name.value;
        const password = form.elements.password.value;
        const errors = [];

        if (name === "") {
            errors.push("アカウント名を入力してください");
        }

        if (email === "") {
            errors.push("メールアドレスを入力してください");
        } else if (!asciiPattern.test(email) || !emailPattern.test(email)) {
            errors.push("メールアドレスは●●●@×××の形式で、半角英数記号のみで入力してください");
        }

        if (password === "") {
            errors.push("パスワードを入力してください");
        } else {
            if (password.length < passwordMinLength) {
                errors.push(`パスワードは${passwordMinLength}文字以上で入力してください`);
            }
            if (!asciiPattern.test(password)) {
                errors.push("パスワードは半角英数記号のみで入力してください");
            }
        }

        renderErrors(errorArea, errors);

        if (errors.length > 0) {
            event.preventDefault();
        }
    });
}

/**
 * 投稿画面 / 投稿編集画面のバリデーションと文字数カウンタ。
 *
 * 上限文字数はサーバー側の Post::MAX_CONTENT_LENGTH から渡される。
 */
function setupPostValidation() {
    const form = document.getElementById("post-form");
    if (!form) {
        return;
    }

    const maxLength = Number(form.dataset.maxLength);
    const textarea = document.getElementById("post-content");
    const counter = document.getElementById("post-counter");
    const errorArea = document.getElementById("post-client-errors");

    const updateCounter = () => {
        const length = textarea.value.trim().length;
        counter.textContent = `${length} / ${maxLength}`;
        counter.classList.toggle("over", length > maxLength);
    };

    textarea.addEventListener("input", updateCounter);
    updateCounter();

    form.addEventListener("submit", (event) => {
        const length = textarea.value.trim().length;
        const errors = [];

        if (length === 0) {
            errors.push("投稿内容を1文字以上入力してください");
        } else if (length > maxLength) {
            errors.push(`投稿内容は${maxLength}文字以内で入力してください`);
        }

        renderErrors(errorArea, errors);

        if (errors.length > 0) {
            event.preventDefault();
        }
    });
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
