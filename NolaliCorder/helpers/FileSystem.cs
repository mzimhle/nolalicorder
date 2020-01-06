using System;
using System.Collections.Generic;
using System.IO;
using Newtonsoft.Json;

namespace NolaliCorder
{
    public static class FileSystem
    {
        // For logging all messages on the output of the application.
        private static string TAG = typeof(FileSystem).Name;
        // To save the json read from the file.
        public static string json = "";
        // Folder name where today's videos will be at as well as their time table.
        public static string dirVideo = DateTime.Today.ToString("yyyy-MM-dd");
        // The name of the folder we will be downloading to
        // This is the application folder that needs to be created each time an application starts for the first time,
        // This is where we save everything. This is on the Download folder of the application.
        public static string dirRoot = "NolaliCamCorder";
        // The actual global path we will use everywhere.
        public static string dirPath = "";
        // Enum of all directories that will be created as sub folders.
        private enum Directories { logs, videos }

        public static bool createTodaysFolder()
        {
            try
            {
                // First lets check if we have today's folder.
                var dirDownloads = Android.OS.Environment.GetExternalStoragePublicDirectory(Android.OS.Environment.DirectoryDownloads);
                var dirPath = new DirectoryInfo(Path.Combine(dirDownloads.AbsolutePath, dirRoot, "videos", dirVideo));
                // If this folder does not exist, lets create it.
                if (!dirPath.Exists)
                {
                    // Lets create it.
                    dirPath.Create();
                    Log.Notify(TAG, "Today's Video Directory Created : " + dirPath.FullName);
                    Settings.PopulateInfo("Today's Video Directory Created : " + dirPath.FullName);
                }
                return true;
            }
            catch (Exception e)
            {
                Log.Error(TAG, "FileSystem.createTodaysFolder Message : " + e.Message);
                Settings.PopulateInfo("FileSystem.createTodaysFolder Message : " + e.Message);
                return false;
            }
        }

        public static void createApplicationDirectories()
        {
            try
            {
                Log.Notify(TAG, "Create Application Folders");
                Settings.PopulateInfo("Create Application Folders");
                // When the application starts, lets make sure that all folders are created before we begin.
                var dirDownloads = Android.OS.Environment.GetExternalStoragePublicDirectory(Android.OS.Environment.DirectoryDownloads);
                var dirPath = new DirectoryInfo(Path.Combine(dirDownloads.AbsolutePath, dirRoot));
                // Make sure the root directory is created.
                Log.Notify(TAG, "Root Directory : " + dirDownloads.AbsolutePath + dirRoot);
                dirPath.Create();
                // Create sub directories. 
                if (dirPath.Exists)
                {
                    Log.Notify(TAG, "Root Directory Created : " + dirDownloads.AbsolutePath + dirRoot);
                    var logs = dirPath.CreateSubdirectory(Directories.logs.ToString()).FullName + "/";
                    Log.Notify(TAG, "Sub Directory Created : " + logs);
                    var videos = dirPath.CreateSubdirectory(Directories.videos.ToString()).FullName + "/";
                    Log.Notify(TAG, "Sub Directory Created : " + videos);
                }
            }
            catch (Exception e)
            {
                Log.Error(TAG, "FileSystem.createApplicationDirectories Message : " + e.Message);
            }
        }
    }
}