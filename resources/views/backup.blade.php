<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء نسخة احتياطية</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h1 class="text-lg font-medium text-gray-800 mb-4">إنشاء نسخة احتياطية</h1>
            <form action="{{ route('backup') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="path" class="block text-gray-700">مسار التخزين:</label>
                    <div class="flex">
                        <input type="text" id="path" name="path" class="form-input mt-1 block w-full" required readonly>
                        <button type="button" onclick="openFilePicker()" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">اختر مسار</button>
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">إنشاء نسخة احتياطية</button>
            </form>
        </div>
    </div>

    <input type="file" id="filePicker" webkitdirectory style="display: none;" onchange="setPath()">

    <script>
        function openFilePicker() {
            document.getElementById('filePicker').click();
        }

        function setPath() {
            var input = document.getElementById('filePicker');
            var path = input.files[0].path;
            document.getElementById('path').value = path;
            
        }
    </script>
</body>
</html>
