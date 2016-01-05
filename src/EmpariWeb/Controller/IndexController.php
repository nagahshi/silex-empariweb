<?php

namespace EmpariWeb\Controller;

use Silex\Application;
use EmpariWeb\Controller\Connection;
use Symfony\Component\HttpFoundation\Request;
class IndexController extends BaseController
{

    public function mount($controller)
    {
        $controller->get('/', array($this, 'home'))->bind('home');
        $controller->get('/contact', array($this, 'contact'))->bind('contact');
        $controller->post('/contact', array($this, 'sendContact'))->bind('sendContact');
        $controller->get('/sobre', array($this, 'about'))->bind('about'); 
        $controller->get('/blog', array($this, 'blog'))->bind('blog'); 
        $controller->get('/{page}', array($this, 'page'))->bind('page'); 
    }

    public function contact(Application $app)
    {
        $con = new Connection($app);
        $customer = $con->get('customer');
        $phones = $con->get('customerPhone');
        $customer['principal_phone'] = $con->principal($phones)['phone_number'];       
        $customer['networks'] = $con->get('customerNetwork')['data'];                
        
        return $app['twig']->render('contact.html', compact('customer'));
    }

    public function home(Application $app)
    {
        $con = new Connection($app);
        $customer = $con->get('customer');
        $phones = $con->get('customerPhone');
        $customer['principal_phone'] = $con->principal($phones)['phone_number']; 
        $customer['networks'] = $con->get('customerNetwork')['data'];  
        $banners = $con->get('banner')['data'];
        $banner = $banners[array_search('Principal', $banners)];

        return $app['twig']->render('index.html', compact('customer', 'banner'));
    }

    public function sendContact(Request $request,Application $app)
    {
        $data['name'] =  $request->get('name');
        $data['email'] =  $request->get('email');
        $data['message'] =  $request->get('message');
        $con = new Connection($app);
        $con->post('customer/shotemail',$data);
        
       return $app->redirect('/');
    }
    
    public function about(Application $app)
    {
        $con = new Connection($app);
        $customer = $con->get('customer');
        $phones = $con->get('customerPhone');
        $customer['principal_phone'] = $con->principal($phones)['phone_number'];       
        $customer['networks'] = $con->get('customerNetwork')['data']; 
        $page = $con->get('page/full/TORQUE');
        $page['image'] = $con->image($page['image']->id);
        return $app['twig']->render('about.html', compact('customer','page'));
    }
    
    public function blog(Application $app)
    {
        $con = new Connection($app);
        $customer = $con->get('customer');
        $phones = $con->get('customerPhone');
        $customer['principal_phone'] = $con->principal($phones)['phone_number'];       
        $customer['networks'] = $con->get('customerNetwork')['data']; 
        $pages = array();
       
        foreach ($con->get('page/')['data'] as $key => $value){
            if($value->parent_id != NULL){
                $pages[$key] = (array) $con->get('page/full/'.$value->slug);
                $pages[$key]['image'] = $con->image($con->get('page/full/'.$value->slug)['image_id']);
            }
        }    
        return $app['twig']->render('blog.html', compact('customer','pages'));
    }
    
    public function page(Application $app, Request $request)
    {
        $con = new Connection($app);
        $customer = $con->get('customer');
        $phones = $con->get('customerPhone');
        $customer['principal_phone'] = $con->principal($phones)['phone_number'];       
        $customer['networks'] = $con->get('customerNetwork')['data']; 
        $url = $request->getRequestUri();
        $page = $con->get("page/full$url");
        $page['image'] = $con->image($page['image']->id);
        return $app['twig']->render('about.html', compact('customer','page'));
    }
}
