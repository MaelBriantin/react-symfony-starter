
import { useState } from "react";
import "./App.css";

type ItemType = {
  name: string,
  url: string,
  src: string
};

const items: ItemType[] = [
  { name: "Vite", url: "https://vite.dev", src: "/vite.svg" },
  { name: "React", url: "https://react.dev", src: "/react.svg"},
];

const itemCard = (item: ItemType) => {
  const hoverColor = item.name === "Vite" ? "hover:text-vite" : "hover:text-react";
  const shadowColor = item.name === "Vite" ? "hover:drop-shadow-[0_0_2em_var(--color-vite)]" :  "hover:drop-shadow-[0_0_2em_var(--color-react)]";
  
  return (
    <div className="flex justify-center flex-col gap-2" key={item.name}>
      <a href={item.url} target="_blank">
        <img
          src={item.src}
          alt={`${item.name} logo`}
          className={[
            "h-[10em]",
            "p-[1.5em]",
            `${shadowColor}`,
          ].join(" ")}
        />
      </a>
      <a
        href={item.url}
        target="_blank"
        className={`text-4xl font-bold text-center transition-colors ${hoverColor}`}
      >
        {item.name}
      </a>
    </div>
  );
}
      
  

const App = () => {
  const [count, setCount] = useState(0);

  return (
      <div className="flex flex-col w-screen h-screen items-center justify-center gap-6 dark:bg-neutral-800 dark:text-gray-100 text-black bg-neutral-100">
        <div className="flex justify-center gap-8">
          {items.map(item => 
            itemCard(item)
          )}
        </div>
        <div className="px-4 py-2 flex flex-col items-center justify-center gap-4">
          <button
            type="button"
            onClick={() => setCount((count: number) => count + 1)}
          >
            count is {count}
          </button>
          <p>
            Edit <code>src/App.tsx</code> and save to test HMR
          </p>
        </div>
        <p className="text-gray-400">
          Click on the Vite, React and Deno logos to learn more
        </p>
      </div>
  );
}

export default App;
