using System;
using System.Collections.Generic;
using System.IO;
using Newtonsoft.Json;
using Android.Widget;
using System.Threading.Tasks;
using Android.App;
using Android.Content;

namespace NolaliCorder
{
    /* This is the class that gets the config, either be it mp3 or normal config. */
    public static class Settings
    {
        /* Tag setting. */
        private static string TAG = typeof(Settings).Name;
        /* Declare the config to be returned, make it type <object> cause it will be an object declared at a later stage. */
        public static Config config;
        public static string json;
        public static VideoView videoview;
        public static TextView info;
        /* Create a constructor to start this. */
        public static async Task getConfig()
        {
            try
            {
                var dirDownloads = Android.OS.Environment.GetExternalStoragePublicDirectory(Android.OS.Environment.DirectoryDownloads);
                var configfile = Path.Combine(dirDownloads.AbsolutePath, "NolaliCamCorder", "config.txt");
                Log.Notify(TAG, "Configuration File : " + configfile);
                /* Check if config has been set already. */
                if (File.Exists(configfile))
                {
                    /* Read the file. */
                    using (var reader = new StreamReader(configfile))
                    {
                        json = reader.ReadToEnd();
                    }
                    config = JsonConvert.DeserializeObject<Config>(json);
                    // check if the id exists.
                    if (config.id == "")
                    {
                        Toast.MakeText(Application.Context, "No ID set for this device.", ToastLength.Long).Show();
                    }
                    else
                    {
                        Toast.MakeText(Application.Context, "GUID - " + config.id, ToastLength.Long).Show();
                    }
                }
                else {
                    Log.Error(TAG, "The configuration file was not found");
                    Settings.PopulateInfo("Configuration File Not Found, please remedy and restart the application");
                }
            }
            catch (Exception e)
            {
                Log.Error(TAG, "Settings.getConfig Message : " + e.Message);
            }
        }
        
        public static void PopulateInfo(String info)
        {
            // Display it on the TextView.
            Settings.info.Text = info;
        }
    }
}