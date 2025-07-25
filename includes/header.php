<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Registration App'; ?></title>
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa; 
            color: #333;
            line-height: 1.6;
            min-height: 100vh; 
            display: flex;
            flex-direction: column;
        }

        /* Header styling */
        header {
            background-color: #007bff; 
            color: #ffffff; 
            text-align: center;
            padding: 1rem;
            width: 100%;
        }

        header h1 {
            font-size: 1.8rem;
        }

        /* Form container styling for login and register */
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        .form-container h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        .form-container input[type="email"],
        .form-container input[type="password"],
        .form-container input[type="text"],
        .form-container select,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-container textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-container select {
            appearance: none;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12"><polygon fill="#333" points="0,0 12,0 6,12"/></svg>') no-repeat right 10px center;
            background-size: 12px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container .msg {
            text-align: center;
            margin-top: 10px;
            color: #007bff;
        }

        .form-container .msg.error {
            color: red;
        }

        .form-container .msg a {
            color: #007bff;
            text-decoration: none;
        }

        .form-container .msg a:hover {
            text-decoration: underline;
        }

        /* Dashboard styling */
        .dashboard {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex: 1;
        }

        .dashboard h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        .dashboard p {
            margin: 10px 0;
            font-size: 1rem;
        }

        .dashboard p strong {
            color: #007bff;
        }

        .profile-circle {
            width: 80px;
            height: 80px;
            background-color: #007bff;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            margin: 0 auto 20px;
        }

        .dashboard button {
            padding: 10px 20px;
            background-color: #dc3545; /* Red for logout */
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dashboard button:hover {
            background-color: #c82333;
        }

        /* Footer styling */
        footer {
            background-color: #004080; 
            color: #ffffff;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            margin-top: auto; 
        }

        footer .footer-content {
            max-width: 900px;
            margin: auto;
            padding: 0 15px;
        }

        footer p {
            margin: 5px 0;
            font-size: 1rem;
        }

        footer a {
            color: #ffffff;
            text-decoration: underline;
            margin: 0 5px;
        }

        footer a:hover {
            text-decoration: none;
        }

        footer .slogan {
            margin-top: 10px;
            font-size: 0.9rem;
            font-style: italic;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .form-container, .dashboard {
                max-width: 90%;
                margin: 30px auto;
                padding: 15px;
            }

            header h1 {
                font-size: 1.6rem;
            }

            .form-container h2, .dashboard h2 {
                font-size: 1.4rem;
            }

            .form-container input,
            .form-container select,
            .form-container textarea,
            .form-container button,
            .dashboard button {
                font-size: 0.9rem;
                padding: 8px;
            }

            .profile-circle {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .form-container, .dashboard {
                max-width: 95%;
                margin: 20px auto;
                padding: 10px;
            }

            header h1 {
                font-size: 1.4rem;
            }

            .form-container h2, .dashboard h2 {
                font-size: 1.2rem;
            }

            footer p, footer .slogan {
                font-size: 0.8rem;
            }

            footer a {
                display: inline-block;
                margin: 2px 0;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>Amtech Technology</h1>
</header>

