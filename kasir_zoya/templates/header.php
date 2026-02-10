<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Zoya - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --pink-zoya: #ff69b4;
            --black-zoya: #212529;
            --white-zoya: #ffffff;
        }
        body { background-color: #fcbde2; overflow-x: hidden; }
        
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background-color: var(--black-zoya);
            color: #fff;
            min-height: 100vh;
            transition: all 0.3s;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #1a1d20;
            text-align: center;
            border-bottom: 1px solid #333;
        }
        #sidebar .sidebar-header h3 { color: var(--pink-zoya); font-weight: bold; margin: 0; }
        #sidebar ul p { color: #fff; padding: 10px; }
        #sidebar ul li a {
            padding: 15px 20px;
            display: block;
            color: #adb5bd;
            text-decoration: none;
            transition: 0.3s;
        }
        #sidebar ul li a:hover {
            color: var(--pink-zoya);
            background: rgba(255, 105, 180, 0.1);
        }
        #sidebar ul li.active > a {
            color: #fff;
            background: var(--pink-zoya);
        }
        
        #content { width: 100%; }
        .navbar { background-color: var(--white-zoya); border-bottom: 2px solid var(--pink-zoya); }
        .btn-pink { background-color: var(--pink-zoya); color: white; }
        .btn-pink:hover { background-color: #e0569e; color: white; }
        .text-pink { color: var(--pink-zoya) !important; }
    </style>
</head>
<body>
    <div class="d-flex">