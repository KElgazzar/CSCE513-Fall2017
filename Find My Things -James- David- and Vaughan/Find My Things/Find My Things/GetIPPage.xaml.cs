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

namespace Find_My_Things
{
    /// <summary>
    /// Interaction logic for GetIPPage.xaml
    /// </summary>
    public partial class GetIPPage : Page
    {
        public GetIPPage()
        {
            InitializeComponent();
        }

        private void Button_Click(object sender, RoutedEventArgs e)
        { 
            if(txtIp.Text == string.Empty)
            {
                txtInfo.Text = "Please enter an ip address to continue";
                return;
            }
            
            HomePage hp = new HomePage(txtIp.Text);

            NavigationService.Navigate(hp);
        }
    }
}
