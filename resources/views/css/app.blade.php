<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    background: #ffffff;
    color: #000;
    margin: 20px;
}

h2 {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
}

a {
    color: #0066cc;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.table-wrapper {
    border: 1px solid #ddd;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

thead {
    background: #f0f0f0;
}

th, td {
    padding: 8px 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
    vertical-align: middle;
}

th {
    font-weight: bold;
    color: #0066cc;
}

tr:hover {
    background: #f9f9f9;
}

.col-name {
    width: 60%;
}

.col-size {
    width: 15%;
}

.col-date {
    width: 20%;
}

.col-actions {
    width: 5%;
    white-space: nowrap;
}

.icon {
    font-size: 14px;
}

/* New */

.app-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 24px;
    background: #ffffff;
    border-bottom: 1px solid #e0e0e0;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.logo {
    font-size: 22px;
}

.app-name {
    font-size: 18px;
    font-weight: 500;
    color: #202124;
    white-space: nowrap;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #5f6368;
    overflow: hidden;
}

.breadcrumb a {
    color: #1a73e8;
    text-decoration: none;
    white-space: nowrap;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.header-right {
    display: flex;
    align-items: center;
}

.search-box input {
    width: 260px;
    padding: 8px 14px;
    border-radius: 24px;
    border: 1px solid #dadce0;
    background: #f1f3f4;
    font-size: 14px;
    outline: none;
}

.search-box input:focus {
    background: #ffffff;
    border-color: #1a73e8;
}

/* Responsivo */
@media (max-width: 768px) {
    .breadcrumb {
        display: none;
    }

    .search-box input {
        width: 180px;
    }
}

</style>
