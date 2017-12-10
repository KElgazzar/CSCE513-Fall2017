using Find_My_Things.CoAPManager;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;
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

namespace Find_My_Things
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    /// 

    public partial class MainWindow : Window
    {
        public MainWindow()
        {
            InitializeComponent();

            if (TestConnection())
            {
                HomePage hp = new HomePage("10.0.1.2");
                myFrame.NavigationService.Navigate(hp);                
            }
            else
            {
                GetIPPage ip = new GetIPPage();
                myFrame.NavigationService.Navigate(ip);
            }
            myFrame.NavigationUIVisibility = NavigationUIVisibility.Hidden;
        }

        private bool TestConnection()
        {
            COAPManager _coap = new COAPManager("10.0.1.2");

            return _coap.GetRequest();
        }
    }
}
