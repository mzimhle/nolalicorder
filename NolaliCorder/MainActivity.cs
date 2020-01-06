using System;
using Android.App;
using Android.OS;
using Android.Runtime;
using Android.Support.V7.App;
using Android.Widget;
using Android.Media;
using System.Timers;
using System.Threading.Tasks;
using System.Threading;
using Android;
using Android.Support.V4.Content;
using Android.Support.V4.App;
using Android.Content.PM;

namespace NolaliCorder
{
    [Activity(Label = "@string/app_name", Theme = "@style/mySplash", MainLauncher = true)]
    public class MainActivity : AppCompatActivity
    {
        #region All properties.
        // For logging all messages on the output of the application.
        private string TAG = typeof(MainActivity).Name;
        private Task someTask;
        #endregion
        protected async override void OnCreate(Bundle savedInstanceState)
        {
            try
            {
                Log.Notify(TAG, "Start the application.");
                base.OnCreate(savedInstanceState);
                // activity_main is the name of the file in resources/layout/activity_main.axml
                SetContentView(Resource.Layout.activity_main);
                // Get the viewing place for the video.
                Settings.videoview = FindViewById<VideoView>(Resource.Id.SampleVideoView);
                Settings.info = FindViewById<TextView>(Resource.Id.info);
                // Create directory and sub directories. 
                FileSystem.createApplicationDirectories();
                // Get configuration
                someTask = Settings.getConfig();
                await someTask;
                // Before we start the project, lets make sure that we have the configuration task completed.
                if (someTask.IsCompleted == true)
                {
                    // Check now if we have settings. 
                    if (Settings.config.id == "" || Settings.config.id == null)
                    {
                        // We do not have a configuration file.
                        Settings.PopulateInfo("Configuration File ID - Not Found");
                    }
                    else {
                        // LETS START THE PROGRAM!
                        someTask = StartProgram();
                        await someTask;
                    }
                }
                else {
                    // Should restart the program after this.
                }
            }
            catch (Exception e)
            {
                Log.Error(TAG, e.Message);
                Log.Error(TAG, e.StackTrace);
            }
        }
        /// <summary>
        /// Timer event that runs to upload videos to the API database.
        /// </summary>
        /// 
        private async Task StartProgram()      
        {
            await Task.Run(() => {

                var startTimeSpan = TimeSpan.Zero;
                var periodTimeSpan = TimeSpan.FromSeconds(20);

                var timer = new System.Threading.Timer( async (e) =>
                {
                    if ((someTask != null) && (someTask.IsCompleted == false || someTask.Status == TaskStatus.Running ||  someTask.Status == TaskStatus.WaitingToRun || someTask.Status == TaskStatus.WaitingForActivation))
                    {
                        // Log.Notify(TAG, "Task is already running");
                    }
                    else {
                        // Create folders for today's videos.
                        if (FileSystem.createTodaysFolder())
                        {
                            // To check if there are any schedules for today before we upload today's videos.
                            Boolean SomethingToDownload = false;
                            // Get the current time stamp.
                            WebService.currentTimeTable();
                            // Get the current time.
                            TimeSpan now = DateTime.Now.TimeOfDay;

                            if (WebService.timeTableObject.result)
                            {
                                
                                // Before we play the video, lets check if the time is right.
                                foreach (var schedule in WebService.timeTableObject.schedule)
                                {
                                    // Lets define our times
                                    TimeSpan start = TimeSpan.Parse(schedule.start);
                                    TimeSpan end = TimeSpan.Parse(schedule.end);                                    
                                    // Check if we have something scheduled now.
                                    if (now >= start && now <= end)
                                    {
                                        SomethingToDownload = true;
                                        break;
                                    }
                                    else if (now < end)
                                    {
                                        SomethingToDownload = true;
                                        break;
                                    }
                                }
                            }
                            else {
                                Settings.PopulateInfo("NO SCHEDULE RECEIVED FOR TODAY");
                            }
                            // Check if we even have something to download today.
                            if (SomethingToDownload == true || Schedule.RecordingState == Schedule.State.Recording)
                            {
                                if (Schedule.RecordingState == Schedule.State.Recording) {
                                    Log.Notify(TAG, "ACTION: " + Schedule.RecordingState);
                                    Settings.PopulateInfo("ACTION: " + Schedule.RecordingState);
                                    Schedule.CalculateTime(activeSchedule.end);
                                }
                                someTask = Schedule.CheckToRecord();
                                await someTask;
                            }
                            else if (Schedule.RecordingState == Schedule.State.Idle && SomethingToDownload == false)
                            {
                                someTask = WebService.uploadingVideosAsync();
                                await someTask;
                            }
                        }
                        else {
                            Log.Notify(TAG, "Today's folders not created.");
                        }
                    }
                }, null, startTimeSpan, periodTimeSpan);

            });
        }
    }
}