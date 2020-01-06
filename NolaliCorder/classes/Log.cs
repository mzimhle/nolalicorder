using System;
using System.IO;

namespace NolaliCorder
{
    public class Log
    {
        private static string strReturn = string.Empty;
        private static string filename = DateTime.Today.ToString("yyyy-MM-dd") + ".txt";

        public static void Notify(string tag, string message)
        {
            strReturn = "NOTIFY | " + tag + " | " + message;
            SaveLogs(strReturn);
        }

        public static void Error(string tag, string message)
        {
            strReturn = "ERROR | " + tag + " | " + message;
            Console.Error.WriteLine(strReturn);
            SaveLogs(strReturn);
        }

        private static void SaveLogs(string logs)
        {
            try
            {
                // First lets check if we have today's folder.
                var dirDownloads = Android.OS.Environment.GetExternalStoragePublicDirectory(Android.OS.Environment.DirectoryDownloads);
                var logPath = new DirectoryInfo(Path.Combine(dirDownloads.AbsolutePath, FileSystem.dirRoot, "logs"));
                // Just double check if the folders exist, no needed but just nje
                if (!logPath.Exists)
                {
                    logPath.Create();
                }
                // If its created, now letes make sure that we write something to this file.
                using (StreamWriter writer = File.AppendText(Path.Combine(dirDownloads.AbsolutePath, FileSystem.dirRoot, "logs", filename)))
                {
                    writer.WriteLine(DateTime.Now + " : " + logs);
                }
            }
            catch (Exception)
            {

            }

        }
    }
}