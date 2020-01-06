using System;
using System.IO;
using Newtonsoft.Json;
using System.Net;
using System.Threading.Tasks;
using System.Threading;
using System.Net.Http;
using System.Net.Http.Headers;
using Android.Support.V4.App;
using Android;
using Android.Content.PM;
using Android.Widget;
using Android.App;

namespace NolaliCorder
{
    public static class WebService
    {
        // For logging all messages on the output of the application.
        private static string TAG = typeof(WebService).Name;
        public static TimeTable timeTableObject = JsonConvert.DeserializeObject<TimeTable>("{\"day\":\"\",\"code\":\"\",\"schedule\":[{\"code\":\"\",\"start\":\"00:00\",\"end\":\"00:00\"}],\"message\":\"Player ID has not been found\",\"result\":0}");

        public static void currentTimeTable()
        {
            try
            {
                // Check first if we are given permission to do this.
                if (ActivityCompat.CheckSelfPermission(Android.App.Application.Context, Manifest.Permission.Camera) == (int)Permission.Granted)
                {
                    string response = "";
                    /* Now what we going to do here is to check if today's video schedule is available, if not, 
                        * we get from the website, otherwise, we get it from the one in the current 
                        * video folder of today. */
                    var dirDownloads = Android.OS.Environment.GetExternalStoragePublicDirectory(Android.OS.Environment.DirectoryDownloads);
                    var schedulePath = Path.Combine(dirDownloads.AbsolutePath, FileSystem.dirRoot, "videos", FileSystem.dirVideo, "schedule.json");
                    // Check if file exists.
                    if (File.Exists(schedulePath))
                    {
                        string j;
                        /* Get the file from the path then. */
                        using (var reader = new StreamReader(schedulePath))
                        {
                            j = reader.ReadToEnd();
                        }
                        timeTableObject = JsonConvert.DeserializeObject<TimeTable>(j);
                    }
                    else
                    {
                        string url = Settings.config.url + "api/" + Settings.config.api + "/" + Settings.config.account;

                        using (var client = new WebClient())
                        {
                            // Setup the headers to send to api to ensure correct information is being returned by the api
                            client.Headers.Add("playerid", Settings.config.id);
                            client.Headers.Add("playername", Settings.config.name);
                            response = client.DownloadString(url);
                            // Popuate or deserialise response to temp Bundle object
                            timeTableObject = JsonConvert.DeserializeObject<TimeTable>(response);
                            // If its created, now letes make sure that we write something to this file.
                            using (StreamWriter writer = new StreamWriter(schedulePath, false))
                            {
                                writer.WriteLine(response);
                            }
                            Settings.PopulateInfo("Received today's schedule....");
                        }
                    }
                } else {
                    Log.Error(TAG, "Permissions NOT granted.");
                    Settings.PopulateInfo("Permissions NOT granted.");
                }
            }
            catch (WebException e)
            {
                switch (e.Status)
                {
                    case WebExceptionStatus.ConnectFailure:
                        Log.Error(TAG, "currentTimeTable - No internet connection.");
                        Settings.PopulateInfo("No internet connection.");
                        break;
                    case WebExceptionStatus.UnknownError:
                        Log.Error(TAG, "currentTimeTable - Unknown error. Check data or interenet connection");
                        Settings.PopulateInfo("Unknown error. Check data or interenet connection");
                        break;
                }
            }
            catch (Exception e)
            {
                Log.Error(TAG, "WebService.currentTimeTable - Exception - " + e.Message);
            }
        }
        /// <summary>
        /// Gets a list of all files and uploads them all to the server.
        /// </summary>        
        public static async Task uploadingVideosAsync()
        {
            string urlUpload = Settings.config.url + "api/" + Settings.config.api + "/" + Settings.config.account + "/upload/";

            try
            {
                if (Schedule.RecordingState == Schedule.State.Idle)
                {
                    var dirDownloads = Android.OS.Environment.GetExternalStoragePublicDirectory(Android.OS.Environment.DirectoryDownloads);
                    var path = Path.Combine(dirDownloads.AbsolutePath, FileSystem.dirRoot, "videos", FileSystem.dirVideo);                    

                    foreach (string file in Directory.EnumerateFiles(path, "*.mp4"))
                    {
                        /* Check if file has been uploaded first. */
                        using (var client = new HttpClient())
                        {
                            // Must run forever
                            client.Timeout = Timeout.InfiniteTimeSpan;
                            // Get the file.
                            using (var stream = File.OpenRead(file))
                            {
                                var content = new MultipartFormDataContent();
                                var file_content = new ByteArrayContent(new StreamContent(stream).ReadAsByteArrayAsync().Result);
                                file_content.Headers.ContentType = new MediaTypeHeaderValue("video/mp4");
                                file_content.Headers.ContentDisposition = new ContentDispositionHeaderValue("attachment")
                                {
                                    FileName = Path.GetFileName(file),
                                    Name = "uploadVideo"
                                };
                                content.Add(file_content);
                                // Add headers
                                content.Headers.Add("playerid", Settings.config.id);
                                content.Headers.Add("playername", Settings.config.name);
                                Settings.PopulateInfo("Uploading the file: " + file);
                                var response = await client.PostAsync(new Uri(urlUpload), content);
                                if (response.StatusCode == HttpStatusCode.Accepted || response.StatusCode == HttpStatusCode.OK)
                                {
                                    response.EnsureSuccessStatusCode();

                                    string responseBody = await response.Content.ReadAsStringAsync();
                                    
                                    Output outputObject = JsonConvert.DeserializeObject<Output>(responseBody);

                                    if (outputObject.result == 2)
                                    {
                                        Log.Notify(TAG, outputObject.message);
                                        File.Delete(file);
                                        Log.Notify(TAG, "File deleted : " + file);
                                        Settings.PopulateInfo("File deleted : " + file);
                                    }
                                    responseBody = null;
                                    response.Dispose();
                                }
                                else
                                {
                                    Log.Notify(TAG, "Result not Accepted or OK : " + response.StatusCode);
                                }
                                stream.Dispose();
                            }
                                
                        }
                        break;
                    }
                }
            }
            catch (WebException e)
            {
                switch (e.Status)
                {
                    case WebExceptionStatus.ConnectFailure:
                        Log.Error(TAG, "Download suspended. No internet connection. - " + urlUpload);
                        break;
                    case WebExceptionStatus.UnknownError:
                        Log.Error(TAG, "Download suspended. Unknown error. Check data or interenet connection");
                        break;
                }                
            }
            catch (Exception e)
            {
                Log.Error(TAG, "uploadingVideosAsync - " + e.Message);
            }
        }
    }
}