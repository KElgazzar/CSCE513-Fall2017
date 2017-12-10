using EXILANT.Labs.CoAP.Channels;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using EXILANT.Labs.CoAP.Message;
using System.Collections;
using EXILANT.Labs.CoAP.Helpers;
using System.Threading;
using Find_My_Things.DataModels;
using Find_My_Things.CoAPManager;
using System.Timers;

namespace Find_My_Things
{
    /// <summary>
    /// Interaction logic for HomePage.xaml
    /// </summary>
    public partial class HomePage : Page
    {
        /// <summary>
        /// Holds the instance of the CoAP client channel object
        /// </summary>
        private COAPManager _coapManager = null;

        private float _threshold = 0;

        int delay = 10000;

        private List<Service> observeables = new List<Service>();

        bool click = true;
        /// <summary>
        /// Used for matching request / response / associated request
        /// </summary>
        private string _mToken = "";

        private Service _popupObserve = null;

        private Service _popupInteract = null;

        private Service _selectedService = null;        

        /// <summary>
        /// The entry point method
        /// </summary>

        public HomePage(string s)
        {
            InitializeComponent();
            SetupPage(s);
        }

        private void SetupPage(string s)
        {           
            _coapManager = new COAPManager(s);
            cbServices.Items.Add("All");
            cbServices.Items.Add("Observe");
            cbServices.Items.Add("Interact");
            cbServices.SelectedIndex = 0;
            btnRefresh_Click(null, null);
        }

        private void Button_Click(object sender, RoutedEventArgs e)
        {
            Application.Current.Dispatcher.BeginInvoke((Action)(() => { gridError.Visibility = Visibility.Visible; }));
            Application.Current.Dispatcher.BeginInvoke((Action)(() => { txtError.Text = "Device Response Pending..."; }));

            if (click)
            {
                _coapManager.SendMessage("0", _selectedService.Location);
            }
            else
            {
                _coapManager.SendMessage("1", _selectedService.Location);
            }

            Application.Current.Dispatcher.BeginInvoke((Action)(() => { txtError.Text = "Message Sent"; }));
        }

        private void OnCoAPError(Exception e, AbstractCoAPMessage associatedMsg)
        {
            throw new NotImplementedException();
        }

        private void OnCoAPRequestReceived(CoAPRequest coapReq)
        {
            throw new NotImplementedException();
        }

        private void OnCoAPResponseReceived(CoAPResponse coapResp)
        {
            string tokenRx = (coapResp.Token != null && coapResp.Token.Value != null) ? AbstractByteUtils.ByteToStringUTF8(coapResp.Token.Value) : "";
            if (tokenRx == _mToken)
            {
                //This response is against the NON request for getting temperature we issued earlier
                if (coapResp.Code.Value == CoAPMessageCode.CONTENT)
                {
                    //Get the temperature
                    string tempAsJSON = AbstractByteUtils.ByteToStringUTF8(coapResp.Payload.Value);

                    //  Light light = Light.Deserialize(tempAsJSON);
                    // Hashtable tempValues = JSONResult.FromJSON(tempAsJSON);
                    // int temp = Convert.ToInt32(tempValues["temp"].ToString());
                    // int temp = Convert.ToInt32(tempAsJSON);
                    //Now do something with this temperature received from the server
                    Application.Current.Dispatcher.BeginInvoke((Action)(() => { gridError.Visibility = Visibility.Visible; }));
                    Application.Current.Dispatcher.BeginInvoke((Action)(() => { txtError.Text = $"Response received: {tempAsJSON}"; }));                  
                }
                else
                {
                    //Will come here if an error occurred..
                }
            }
        }

        private void btnRefresh_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                Application.Current.Dispatcher.BeginInvoke((Action)(() => { gridError.Visibility = Visibility.Collapsed; }));
                txtError.Text = "";
                _selectedService = null;
                gridInteract.Visibility = Visibility.Collapsed;
                gridObserve.Visibility = Visibility.Collapsed;
                if (_coapManager.GetRequest())
                {
                    observeables.Clear();
                    foreach (var item in _coapManager.ObserveServices)
                    {
                        observeables.Add(item);
                    }

                    setLVSource();
                }
                else
                {
                    Application.Current.Dispatcher.BeginInvoke((Action)(() => { gridError.Visibility = Visibility.Visible; }));
                    Application.Current.Dispatcher.BeginInvoke((Action)(() => { txtError.Text = "Unable to retrieve IoT Services."; }));

                    lvServices.ItemsSource = null;
                }
            }
            catch (Exception)
            {
                Application.Current.Dispatcher.BeginInvoke((Action)(() => { gridError.Visibility = Visibility.Visible; }));
                Application.Current.Dispatcher.BeginInvoke((Action)(() => { txtError.Text = "Unable to retrieve IoT Services."; }));

                lvServices.ItemsSource = null;
            }
        }

        private void setLVSource()
        {
            lvServices.Items.Clear();

            if (cbServices.SelectedIndex == 0)
            {
                InvertItemColors(_coapManager.Services);
            }
            else if (cbServices.SelectedIndex == 1)
            {
                InvertItemColors(_coapManager.ObserveServices);            
            }
            else if (cbServices.SelectedIndex == 2)
            {
                InvertItemColors(_coapManager.InteractServices);
            }
        }

        private void InvertItemColors(List<Service> services)
        {
            foreach (var item in services)
            {
                ListViewItem newItem = new ListViewItem();

                newItem.Foreground = new SolidColorBrush(Colors.White);
                newItem.Background = new SolidColorBrush(Colors.Black);
                newItem.Content = item.ToString();
                lvServices.Items.Add(newItem);
            }
        }

        private async void InvertPopupItemColorsAsync(List<Service> services)
        {
            foreach (var item in services)
            {
                ListViewItem newItem = new ListViewItem();

                newItem.Foreground = new SolidColorBrush(Colors.White);
                newItem.Background = new SolidColorBrush(Colors.Black);
                newItem.Content = item.ToString();
                lvPopup.Items.Add(newItem);
            }
        }

        private void setSelectedService()
        {
            txtServiceName.Text = "";

            if (cbServices.SelectedIndex == 0)
            {
                try
                {
                    _selectedService = _coapManager.Services[lvServices.SelectedIndex];
                }
                catch (Exception)
                { }
            }
            else if (cbServices.SelectedIndex == 1)
            {
                try
                {
                    _selectedService = _coapManager.ObserveServices[lvServices.SelectedIndex];                
                }
                catch (Exception)
                { }
            }
            else if (cbServices.SelectedIndex == 2)
            {
                try
                {
                    _selectedService = _coapManager.InteractServices[lvServices.SelectedIndex];                    
                }
                catch (Exception)
                { }
            }

            if (_selectedService != null)
                txtServiceName.Text = _selectedService.Name;
        }

        private void cbServices_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            setLVSource();
        }

        private void btnNewIP_Click(object sender, RoutedEventArgs e)
        {
            GetIPPage ip = new GetIPPage();
            NavigationService.Navigate(ip);
        }        

        private void SetInteractStatus()
        {
            string status = _coapManager.GetDeviceStatus(_selectedService.Location);
                
            if (status == "not found")
            {
                txtServiceName.Text = "Device Status not available";
            }
            else if (status == "1")
            {
                txtServiceName.Text = $"{_selectedService.Display()} status: On";
            }
            else if (status == "0")
            {
                txtServiceName.Text = $"{_selectedService.Display()} status: Off";
            }
        }

        private void SetObserveStatus()
        {
            string status = _coapManager.GetDeviceStatus(_selectedService.Location);

            if (status == "not found")
            {
                txtServiceName.Text = "Device Status not available";
            }
            else 
            {
                txtServiceName.Text = $"{_selectedService.Display()} Information";
                PopulateObserveInfo(status);
            }           
        }

        private void PopulateObserveInfo(string info)
        {
            lvObserveInfo.Items.Clear();

            string[] infoArr = info.Split(',');

            string display = "";

            for (int i = 0; i < infoArr.Length; i++)
            {
                if (i %2 == 0)
                {
                    display = infoArr[i].Trim();
                }
                else
                {
                    display += $":  {infoArr[i].Trim()}";
                    ListViewItem newItem = new ListViewItem();

                    newItem.Foreground = new SolidColorBrush(Colors.White);
                    newItem.Background = new SolidColorBrush(Colors.Transparent);
                    newItem.Content = display;
                    newItem.FontSize = 17;
                    lvObserveInfo.Items.Add(newItem);
                }
            }            
        }

        private void lvServices_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            setSelectedService();
            
            if (_selectedService.ResourceType == "observe")
            {
                SetObserveStatus();

                gridInteract.Visibility = Visibility.Collapsed;
                gridObserve.Visibility = Visibility.Visible;
            }
            else if (_selectedService.ResourceType == "interact")
            {
                SetInteractStatus();

                gridObserve.Visibility = Visibility.Collapsed;
                gridInteract.Visibility = Visibility.Visible;
            }
            else
            {
                gridInteract.Visibility = Visibility.Collapsed;
                gridObserve.Visibility = Visibility.Collapsed;
            }
        }

        private void btnTurnOff_Click(object sender, RoutedEventArgs e)
        {
            if (_selectedService == null || _selectedService.ResourceType != "interact")
            {
                return;
            }

            Application.Current.Dispatcher.BeginInvoke((Action)(() => { gridError.Visibility = Visibility.Visible; }));
            Application.Current.Dispatcher.BeginInvoke((Action)(() => { txtError.Text = "Device Response Pending..."; }));
           
            _coapManager.SendMessage("0", _selectedService.Location);
            
            SetInteractStatus();

            Application.Current.Dispatcher.BeginInvoke((Action)(() => { gridError.Visibility = Visibility.Collapsed; }));
            //Application.Current.Dispatcher.BeginInvoke((Action)(() => { txtError.Text = "Message Sent"; }));
        }

        private void btnTurnOn_Click(object sender, RoutedEventArgs e)
        {           
            if (_selectedService == null || _selectedService.ResourceType != "interact")
            {
                return;
            }

            Application.Current.Dispatcher.BeginInvoke((Action)(() => { gridError.Visibility = Visibility.Visible; }));
            Application.Current.Dispatcher.BeginInvoke((Action)(() => { txtError.Text = "Device Response Pending..."; }));

            _coapManager.SendMessage("1", _selectedService.Location);

            SetInteractStatus();

            Application.Current.Dispatcher.BeginInvoke((Action)(() => { gridError.Visibility = Visibility.Collapsed; }));
            //Application.Current.Dispatcher.BeginInvoke((Action)(() => { txtError.Text = "Message Sent"; }));
        }

        private async void btnAddDependency_Click(object sender, RoutedEventArgs e)
        {
            if (_selectedService == null || _selectedService.ResourceType == "observe")
            {
                return;
            }
            
            _popupInteract = _selectedService;

            txtPopupTitle.Text = $"Add {_popupInteract.Display()} Listener";

            InvertPopupItemColorsAsync(observeables);

            PopupIFFT.IsOpen = true;
            gridPopup.Visibility = Visibility.Visible;
        }

        private void btnOK_Click(object sender, RoutedEventArgs e)
        {
            if (_popupObserve == null || txtBoxThreshold.Text == string.Empty || txtBoxInterval.Text == string.Empty)
            {
                return;
            }

            delay = Convert.ToInt32(txtBoxInterval.Text) * 1000;

            _threshold = float.Parse(txtBoxThreshold.Text);

            StartNewListener(_popupObserve, _popupInteract);

            PopupIFFT.IsOpen = false;
            gridPopup.Visibility = Visibility.Collapsed;
        }

        private void StartNewListener(Service observe, Service interact)
        {
            _observe = observe;
            _interact = interact;

            Thread t = new Thread(Listener);

            t.Start();
        }

        private Service _observe = null;

        private Service _interact = null;

        private bool isChanged = false;

        private void Listener()
        {
            System.Timers.Timer timer = new System.Timers.Timer(delay);
            timer.Elapsed += Timer_Elapsed;

            timer.Start();
        }        

        private void Timer_Elapsed(object sender, ElapsedEventArgs e)
        {
            string status = _coapManager.GetDeviceStatus(_popupObserve.Location);            

            if (status == "not found")
            {
                return;                
            }                      

            string[] infoArr = status.Split(',');

            string display = "";

            float value = 0;

            for (int i = 0; i < infoArr.Length; i++)
            {
                if (i % 2 == 1)
                {
                    value = float.Parse(infoArr[i].Trim());
                }                
            }

            if (value > _threshold)
            {
                _coapManager.SendMessage("0", _popupInteract.Location);
            }
            else
            {
                _coapManager.SendMessage("1", _popupInteract.Location);
            }
        }

        private void btnCancel_Click(object sender, RoutedEventArgs e)
        {           
            PopupIFFT.IsOpen = false;
            gridPopup.Visibility = Visibility.Collapsed;
        }

        private async void lvPopup_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            _popupObserve = observeables[lvPopup.SelectedIndex];
        }
    }
}