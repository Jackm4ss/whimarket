import React, { useState, useEffect } from 'react';
import { LandingPage } from './pages/LandingPage';
import { ShopPage } from './pages/ShopPage';

export const App: React.FC = () => {
  const [currentPage, setCurrentPage] = useState<'landing' | 'shop'>('shop');

  useEffect(() => {
    const handleHash = () => {
      const hash = window.location.hash.toLowerCase();
      if (hash === '#belanja' || hash === '#shop') {
        setCurrentPage('shop');
      } else if (hash === '#beranda' || hash === '#home') {
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
