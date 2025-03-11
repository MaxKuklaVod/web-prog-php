// Новый размер поля
const BOARD_WIDTH = 24;  // Ширина поля
const BOARD_HEIGHT = 15; // Высота поля
const NUM_MINES = Math.floor(BOARD_WIDTH * BOARD_HEIGHT * 0.15); // 15% клеток — это мины

let board = [];
let minePositions = [];
let cursor = { x: 0, y: 0 };
let firstClick = true;

const gameBoard = document.getElementById("game-board");

// События
document.addEventListener("mine.start", (event) => {
    console.log("Игра началась!");
});

document.addEventListener("mine.step", (event) => {
    const { x, y } = event.detail;
    console.log(`Ход игрока: (${x}, ${y})`);
});

document.addEventListener("mine.end", (event) => {
    console.log(`Игра окончена. Результат: ${event.detail}`);
});

// Функция для динамического изменения стилей #game-board в зависимости от размера поля
function updateBoardStyle() {
    // Динамически изменяем CSS-свойства
    gameBoard.style.gridTemplateColumns = `repeat(${BOARD_WIDTH}, 40px)`;
    gameBoard.style.gridTemplateRows = `repeat(${BOARD_HEIGHT}, 40px)`;
}

function generateBoard() {
    board = Array.from({ length: BOARD_HEIGHT }, () =>
        Array.from({ length: BOARD_WIDTH }, () => ({
            mine: false,
            open: false,
            flagged: false,
            adjacent: 0
        }))
    );

    gameBoard.innerHTML = "";
    for (let y = 0; y < BOARD_HEIGHT; y++) {
        for (let x = 0; x < BOARD_WIDTH; x++) {
            const cell = document.createElement("div");
            cell.className = "cell";
            cell.dataset.x = x;
            cell.dataset.y = y;
            cell.addEventListener("click", () => openCell(x, y));
            cell.addEventListener("contextmenu", (e) => {
                e.preventDefault();
                toggleFlag(x, y);
            });
            gameBoard.appendChild(cell);
        }
    }

    updateBoardStyle(); // Применяем стили
}

function placeMines(excludeX, excludeY) {
    minePositions = [];
    while (minePositions.length < NUM_MINES) {
        const x = Math.floor(Math.random() * BOARD_WIDTH);
        const y = Math.floor(Math.random() * BOARD_HEIGHT);
        if ((x !== excludeX || y !== excludeY) && !board[y][x].mine) {
            board[y][x].mine = true;
            minePositions.push({ x, y });
        }
    }

    calculateAdjacents();
}

function calculateAdjacents() {
    const directions = [
        [-1, -1], [-1, 0], [-1, 1],
        [0, -1], [0, 1],
        [1, -1], [1, 0], [1, 1]
    ];

    for (let y = 0; y < BOARD_HEIGHT; y++) {
        for (let x = 0; x < BOARD_WIDTH; x++) {
            if (board[y][x].mine) continue;

            directions.forEach(([dx, dy]) => {
                const nx = x + dx;
                const ny = y + dy;
                if (nx >= 0 && ny >= 0 && nx < BOARD_WIDTH && ny < BOARD_HEIGHT && board[ny][nx].mine) {
                    board[y][x].adjacent++;
                }
            });
        }
    }
}



function toggleFlag(x, y) {
    const cell = board[y][x];
    if (cell.open) return;

    cell.flagged = !cell.flagged;
    const cellElement = getCellElement(x, y);
    cellElement.classList.toggle("flag");
    cellElement.textContent = cell.flagged ? "🚩" : "";
}

function getCellElement(x, y) {
    return document.querySelector(`.cell[data-x='${x}'][data-y='${y}']`);
}

function revealMines() {
    minePositions.forEach(({ x, y }) => {
        const cellElement = getCellElement(x, y);
        cellElement.textContent = "💣";
        cellElement.classList.add("open");
    });
}

function checkWin() {
    let openedCells = 0;
    for (let y = 0; y < BOARD_HEIGHT; y++) {
        for (let x = 0; x < BOARD_WIDTH; x++) {
            if (board[y][x].open) openedCells++;
        }
    }
    if (openedCells === BOARD_WIDTH * BOARD_HEIGHT - NUM_MINES) {
        alert("You Win!");
    }
}

function highlightCursor() {
    document.querySelectorAll(".cell").forEach(cell => cell.classList.remove("highlight"));
    getCellElement(cursor.x, cursor.y).classList.add("highlight");
}

document.addEventListener("keydown", (e) => {
    if (e.key === "ArrowUp") cursor.y = Math.max(0, cursor.y - 1);
    if (e.key === "ArrowDown") cursor.y = Math.min(BOARD_HEIGHT - 1, cursor.y + 1);
    if (e.key === "ArrowLeft") cursor.x = Math.max(0, cursor.x - 1);
    if (e.key === "ArrowRight") cursor.x = Math.min(BOARD_WIDTH - 1, cursor.x + 1);

    // Обработка открытия ячейки
    if (e.key === "Enter" || e.key === " ") {
        if (e.ctrlKey) {
            toggleFlag(cursor.x, cursor.y);
        } else {
            openCell(cursor.x, cursor.y);
        }
    }

    highlightCursor();
});
generateBoard();
highlightCursor();

function openCell(x, y) {
    const cell = board[y][x];
    if (cell.flagged) return;

    // Если ячейка уже открыта и кликнули по ней, открыть соседние
    if (cell.open) {
        openAdjacentCells(x, y, skipFlags = true);
        return;
    }

    if (firstClick) {
        placeMines(x, y);
        firstClick = false;

        // Диспатчим событие mine.start
        const startEvent = new CustomEvent("mine.start");
        document.dispatchEvent(startEvent);
    }

    cell.open = true;
    const cellElement = getCellElement(x, y);
    cellElement.classList.add("open");
    // Диспатчим событие mine.step
    const stepEvent = new CustomEvent("mine.step", {
        detail: { x, y }
    });
    document.dispatchEvent(stepEvent);

    if (cell.mine) {
        cellElement.textContent = "💣";
        alert("Game Over!");  // Сообщение о поражении
        revealMines();

        // Диспатчим событие mine.end с результатом "lose"
        const endEvent = new CustomEvent("mine.end", {
            detail: "lose"
        });
        document.dispatchEvent(endEvent);

        // Перезагружаем страницу, чтобы начать новую игру
        setTimeout(() => {
            location.reload(); // Перезагрузка страницы через 1 секунду
        }, 1000);

        return;
    }

    if (cell.adjacent > 0) {
        cellElement.textContent = cell.adjacent;
    } else {
        openAdjacentCells(x, y);
    }

    checkWin();
}


function openAdjacentCells(x, y, skipFlags = false) {
    const directions = [
        [-1, -1], [-1, 0], [-1, 1],
        [0, -1], [0, 1],
        [1, -1], [1, 0], [1, 1]
    ];

    directions.forEach(([dx, dy]) => {
        const nx = x + dx;
        const ny = y + dy;

        if (
            nx >= 0 && ny >= 0 &&
            nx < BOARD_WIDTH && ny < BOARD_HEIGHT &&
            !board[ny][nx].open && // Ячейка еще не открыта
            (!skipFlags || !board[ny][nx].flagged) // Пропускать флаги, если указано
        ) {
            openCell(nx, ny);
        }
    });
}
