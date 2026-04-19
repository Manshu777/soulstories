import React from 'react';
import { createRoot } from 'react-dom/client';
import ChapterWriterApp from './components/ChapterWriterApp';

const el = document.getElementById('chapter-writer-root');
if (el) {
  const root = createRoot(el);
  root.render(<ChapterWriterApp />);
}
