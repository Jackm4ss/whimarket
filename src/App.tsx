import React, { useState, useEffect } from 'react';
import { LandingPage } from './pages/LandingPage';
import { ShopPage } from './pages/ShopPage';

export const App: React.FC = () => {
  const [currentPath, setCurrentPath] = useState<string>(window.location.pathname);

  useEffect(() => {
    const handleLocationChange = () => {
      const path = window.location.pathname.toLowerCase();
      const hash = window.location.hash.toLowerCase();

      // Support /belanja, /shop, and backwards compatibility for hash
      if (
        path.startsWith('/belanja') ||
        path.startsWith('/shop') ||
        hash.startsWith('#belanja') ||
        hash.startsWith('#shop')
      ) {
        setCurrentPath('/belanja');
      } else {
        setCurrentPath('/');
      }
    };

    handleLocationChange();

    window.addEventListener('popstate', handleLocationChange);
    window.addEventListener('hashchange', handleLocationChange);
    return () => {
      window.removeEventListener('popstate', handleLocationChange);
      window.removeEventListener('hashchange', handleLocationChange);
    };
  }, []);

  const navigateTo = (url: string) => {
    window.history.pushState({}, '', url);
    setCurrentPath(url);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  if (currentPath.startsWith('/belanja') || currentPath.startsWith('/shop')) {
    return <ShopPage onNavigateHome={() => navigateTo('/')} />;
  }

  return <LandingPage onNavigateShop={() => navigateTo('/belanja')} />;
};
