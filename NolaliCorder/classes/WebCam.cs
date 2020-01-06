using System;
using System.IO;
using Android.Hardware;
using Android.Media;
using Android.Widget;

namespace NolaliCorder
{
    class WebCam 
    {
        public static string TAG = typeof(WebCam).Name;
        public static MediaRecorder recorder;
        public static Camera mCamera;
        // Start recording sound and video.
        public static bool Record()
        {
            bool outcome;
            try
            {
                // Open the camera.
                if (GetCamera())
                {
                    // First lets check if we have today's folder.
                    var dirDownloads = Android.OS.Environment.GetExternalStoragePublicDirectory(Android.OS.Environment.DirectoryDownloads);
                    var path = Path.Combine(dirDownloads.AbsolutePath, FileSystem.dirRoot, "videos", FileSystem.dirVideo, WebService.timeTableObject.code + ".mp4");
                    
                    recorder = new MediaRecorder();
                    // Set up the media recorder.
                    recorder.SetCamera(mCamera);
                    recorder.SetVideoSource(VideoSource.Camera);
                    recorder.SetAudioSource(AudioSource.Mic);
                    recorder.SetOutputFormat(OutputFormat.Default);
                    recorder.SetVideoEncoder(VideoEncoder.Default);
                    recorder.SetAudioEncoder(AudioEncoder.Aac);
                    recorder.SetOutputFile(path);
                    recorder.SetPreviewDisplay(Settings.videoview.Holder.Surface);
                    recorder.Prepare();
                    recorder.Start();
                    outcome = true;
                    Log.Notify(TAG, "-----------------------------------");
                    Log.Notify(TAG, "STARTED RECORDING");
                }
                else {
                    Log.Error(TAG, "Camera was not found.");
                    outcome = false;
                }
            }
            catch (Java.Lang.IllegalStateException e)
            {
                Log.Error(TAG, "IllegalStateException preparing MediaRecorder: " + e.Message);
                outcome = false;
            }
            catch (IOException e) {
                Log.Error(TAG, "IOException preparing MediaRecorder:  " + e.Message);
                outcome = false;
            }
            catch (Exception e)
            {
                Log.Error(TAG, "WebCam.Record Message : " + e.Message);
                Log.Error(TAG, "WebCam.Record StackTrace : " + e.StackTrace);
                outcome = false;
            }
            return outcome;
        }

        // Start recording sound and video.
        public static bool Stop()
        {
            bool outcome;
            try
            {
                if (recorder != null)
                {
                    recorder.Stop();
                    // recorder.Reset();
                    recorder.Release();
                    // recorder = null;
                    releaseCameraAndPreview();
                }
                outcome = true;
                Log.Notify(TAG, "STOPPED RECORDING");
                Log.Notify(TAG, "-----------------------------------");
            }
            catch (Exception e)
            {
                Log.Error(TAG, "WebCam.Stop Message : " + e.Message);
                Log.Error(TAG, "WebCam.Stop StackTrace : " + e.StackTrace);
                outcome = false;
            }
            return outcome;
        }

        public static bool GetCamera()
        {
            try
            {
                int cameraId = -1;
                int camNums = Camera.NumberOfCameras;
                Log.Notify(TAG, "Number of cameras : " + camNums);
                Settings.PopulateInfo("Number of cameras : " + camNums);
                for (int i = 0; i < camNums; i++)
                {
                    Camera.CameraInfo info = new Camera.CameraInfo();
                    Camera.GetCameraInfo(i, info);

                    if (info.Facing == Camera.CameraInfo.CameraFacingBack)
                    {
                        cameraId = i;
                        break;
                    }
                }
                if (cameraId != -1)
                {
                    mCamera = Camera.Open(cameraId);
                    mCamera.StartPreview();
                    mCamera.Unlock();
                    return true;
                }
                else
                {
                    Log.Notify(TAG, "Camera does not exist.");
                    return false;
                }
            }
            catch (Java.Lang.RuntimeException e) {
                Log.Error(TAG, "WebCam.GetCamera RuntimeException Message : " + e.Message);
                Log.Error(TAG, "WebCam.GetCamera RuntimeException StackTrace : " + e.StackTrace);
                return false;
            }
            catch (Exception e)
            {
                Log.Error(TAG, "WebCam.GetCamera Exception Message : " + e.Message);
                Log.Error(TAG, "WebCam.GetCamera Exception StackTrace : " + e.StackTrace);
                return false;
            }
        }

        private static void releaseCameraAndPreview() {
            if (mCamera != null)
            {
                mCamera.Lock();
                mCamera.StopPreview();
                mCamera.Release();
                mCamera = null;
            }
        }
    }
}