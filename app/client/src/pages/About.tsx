import useUserStore from "@/stores/useUserStore";
import useFetch from "../hooks/useFetch";
import { User } from "@/types/user";
import { useEffect } from "react";

interface Env {
  apiUrl: string;
  clientUrl: string;
}

const About = () => {
  const { data: env } = useFetch<Env>('/config/env');
  const { data } = useFetch<User>('/auth/check');

  const { user, setUser } = useUserStore();

  useEffect(() => {
    if (data && !user) {
      setUser(data);
    }
  }, [user, setUser, data]);

  
  return (
    <div className="dark:bg-neutral-900 dark:text-white bg-white text-black min-h-screen flex items-center justify-center font-geist-mono flex-col gap-2 w-full p-6">
      <h1 className="text-4xl font-bold mb-2">
        { null !== user && (<><span>👋 Hey </span><span className="text-blue-500">{user.email}</span>,</>) }
      </h1>
      <h2 className="text-4xl font-bold">
        Welcome to my React and Symfony Starter Template
      </h2>
      <p className="mt-4">
        This is a simple starter template for React and Symfony.
      </p>
      <div className="m-4">
        It includes
        everything you need to get started with building a modern web application, including:
        <ul className="list-decimal list-inside">
          <li>React 19 as the frontend framework</li>
          <ul className="list-disc list-inside ml-6">
            <li>Vite as the build tool</li>
            <li>TypeScript</li>
            <li>Tailwind CSS with V4 Configuration</li>
            <li>React Router v7 for routing</li>
            <li>Zustand as the state management library</li>
          </ul>
          <li>Symfony as the backend framework (Json API)</li>
          <ul className="list-disc list-inside ml-6">
            <li>Hexagonal Architecture</li>
            <li>Doctrine as the ORM</li>
            <li>Authentication with JWT and HTTP Only cookies</li>
          </ul>
          <li>MySQL as the database</li>
          <li>Caddy as a web server</li>
        </ul>
      </div>
      <p className="mt-4">
        All is dockerized and Taskfile is used to manage the development environment and help you with common tasks.
      </p>
      <p className="mt-4">
        You can access the Symfony API at <a href={env?.apiUrl} className="text-blue-500 hover:underline">{env?.apiUrl}</a>
      </p>
      <br />
      <p className="mt-6 text-xl font-bold text-green-400">
        Happy Hacking!
      </p>
    </div>
  )
}

export default About;
