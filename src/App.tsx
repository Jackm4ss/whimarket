import React, { useState, useEffect } from 'react';
import { LandingPage } from './pages/LandingPage';
import { ShopPage } from './pages/ShopPage';

export const App: React.FC = () => {
  const [currentPage, setCurrentPage] = useState<'landing' | 'shop'>('landing');

  useEffect(() => {
    const handleHash = () => {
      const hash = window.location.hash.toLowerCase();
      if (hash.startsWith('#belanja') || hash.startsWith('#shop')) {
        setCurrentPage('shop');
      } else {
        setCurrentPage('landing');
      }
    };

    handleHash();
    window.addEventListener('hashchange', handleHash);
    return () => window.removeEventListener('hashchange', handleHash);
  }, []);

  if (currentPage === 'shop') {
    return <ShopPage onNavigateHome={() => setCurrentPage('landing')} />;
  }

  return <LandingPage onNavigateShop={() => setCurrentPage('shop')} />;
};
