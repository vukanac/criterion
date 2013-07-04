<?php
namespace CI\UI\Controller;

class ProjectsController
{
    public function all(\Silex\Application $app)
    {
        $projects = $app['mongo']->projects->find()->sort(array(
            '_id' => -1
        ));

        $data['projects'] = array();
        foreach ($projects as $project)
        {
            $data['projects'][] = $project;
        }

        return $app['twig']->render('Projects/All.twig', $data);
    }

    public function run(\Silex\Application $app)
    {
        $client= new \GearmanClient();
        $client->addServer('127.0.0.1', 4730);
        $test_id = $client->doNormal('create_test', $app['request']->get('id'));
        return $app->redirect('/project/' . $app['request']->get('id'));
    }

    public function view(\Silex\Application $app)
    {
        $data['project'] = $app['mongo']->projects->findOne(array(
            '_id' => new \MongoId($app['request']->get('id'))
        ));

        if ($app['request']->getMethod() == 'POST')
        {
            $update_data['repo'] = $app['request']->get('repo');
            $update_data['branch'] = $app['request']->get('branch');

            $update = $app['mongo']->projects->update($data['project'], array(
                '$set' => $update_data
            ));

            return $app->redirect('/project/' . $app['request']->get('id'));

        }

        $tests = $app['mongo']->tests->find(array(
            'project_id' => new \MongoId($app['request']->get('id'))
        ))->sort(array(
            'started' => -1
        ));

        $data['tests'] = array();
        foreach ($tests as $test)
        {
            $data['tests'][] = $test;
        }

        return $app['twig']->render('Projects/View.twig', $data);
    }
}