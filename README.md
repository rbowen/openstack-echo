# openstack-echo

An Amazon Echo skill that knows information about OpenStack

To enable it on your Echo, find it in the Skills menu, and click
'Enable'. (Note: This skill is currently undergoing certification, so
it's not actually there yet.)

To run your own instance, you'll need to know some things about Amazon Echo 
skills, and you'll need an Amazon Echo developer account so that you can
point it at your own running instance of the application.

The information for this all comes from
http://git.openstack.org/cgit/openstack/governance/tree/reference/projects.yaml 
So, if you're running your own instance, you'll also need to:

git clone https://git.openstack.org/openstack/governance

inside the checkout of this repo before anything will work. And 
you'll need to update that occasionally to get the latest information.

With this skill, you can currently do two things:

    Alexa, ask openstack for a random project
    Alexa, ask openstack for a project
    Alexa, ask openstack to tell me about some project
    Alexa, ask openstack to tell me about a project

    Alexa, ask openstack what is project {Project}
    Alexa, ask openstack to tell me about {Project}
    Alexa, ask openstack to tell me about OpenStack {Project}
    Alexa, ask openstack what is the {Project} project
    Alexa, ask openstack what the {Project} project is


