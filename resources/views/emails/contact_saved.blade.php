<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 20px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Details</h1>
        <ul>
            <li>
                <span class="label">Name:</span>
                <span>{{ $contact['name'] }}</span>
            </li>
            <li>
                <span class="label">First Name:</span>
                <span>{{ $contact['firstName'] }}</span>
            </li>
            <li>
                <span class="label">Phone:</span>
                <span>{{ $contact['phone'] }}</span>
            </li>
            <li>
                <span class="label">Subject:</span>
                <span>{{ $contact['subject'] }}</span>
            </li>
            <li>
                <span class="label">Email:</span>
                <span>{{ $contact['email'] }}</span>
            </li>
            <li>
                <span class="label">Category:</span>
                <span>{{ $contact['category'] }}</span>
            </li>
            <li>
                <span class="label">Message:</span>
                <p>{{ $contact['message'] }}</p>
            </li>
        </ul>
    </div>
</body>
</html>
