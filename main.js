let serverProcess;

function createWindow() {
  const win = new BrowserWindow({
    width: 800,
    height: 600,
    webPreferences: {
      nodeIntegration: true,
      contextIsolation: false,
    },
  });

  win.loadURL('http://localhost:8000');

  // تشغيل php artisan serve
  serverProcess = exec('php artisan serve', (error, stdout, stderr) => {
    if (error) {
      console.error(`Error starting server: ${error}`);
      return;
    }
    console.log(`Server output: ${stdout}`);
  });
}

app.on('will-quit', () => {
  if (serverProcess) {
    serverProcess.kill(); // إيقاف الخادم عند إغلاق التطبيق
  }
});