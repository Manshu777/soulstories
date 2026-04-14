import React, { useEffect, useMemo, useRef, useState } from 'react';
import axios from 'axios';

const moods = [
  { value: 'love', label: 'Love' },
  { value: 'heartbreak', label: 'Heartbreak' },
  { value: 'motivation', label: 'Motivation' },
  { value: 'dark', label: 'Dark' },
  { value: 'life', label: 'Life' },
];

const initialForm = {
  prompt: '',
  mood: 'love',
  context: '',
};

function Toast({ toast }) {
  if (!toast) return null;
  const color = toast.type === 'error' ? 'bg-red-600' : 'bg-slate-900';
  return (
    <div className={`fixed top-5 right-5 z-50 text-white px-4 py-2 rounded-xl shadow-lg ${color}`}>
      {toast.message}
    </div>
  );
}

export default function ChapterWriterApp() {
  const [darkMode, setDarkMode] = useState(false);
  const [form, setForm] = useState(initialForm);
  const [result, setResult] = useState({ title: '', content: '', continue_reading_after: 300 });
  const [typedContent, setTypedContent] = useState('');
  const [isGenerating, setIsGenerating] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const [toast, setToast] = useState(null);
  const [showSkeleton, setShowSkeleton] = useState(false);
  const outputRef = useRef(null);

  useEffect(() => {
    if (!toast) return undefined;
    const timer = setTimeout(() => setToast(null), 2400);
    return () => clearTimeout(timer);
  }, [toast]);

  useEffect(() => {
    if (!result.content) {
      setTypedContent('');
      return;
    }

    let i = 0;
    setTypedContent('');
    const text = result.content;
    const timer = setInterval(() => {
      i += 5;
      setTypedContent(text.slice(0, i));
      if (i >= text.length) clearInterval(timer);
    }, 8);

    return () => clearInterval(timer);
  }, [result.content]);

  const wordCount = useMemo(() => {
    if (!result.content) return 0;
    return result.content.trim().split(/\s+/).filter(Boolean).length;
  }, [result.content]);

  const teaserText = useMemo(() => {
    if (!typedContent) return '';
    const words = typedContent.trim().split(/\s+/);
    const max = Number(result.continue_reading_after || 300);
    if (words.length <= max) return typedContent;
    return words.slice(0, max).join(' ') + '...';
  }, [typedContent, result.continue_reading_after]);

  const notify = (message, type = 'success') => setToast({ message, type });

  const generateChapter = async () => {
    if (!form.prompt.trim()) {
      notify('Please enter a story idea first.', 'error');
      return;
    }

    setIsGenerating(true);
    setShowSkeleton(true);

    try {
      const { data } = await axios.post('/api/generate-chapter', {
        prompt: form.prompt,
        mood: form.mood,
        context: form.context,
      });

      setResult({
        title: data.title || 'Untitled Chapter',
        content: data.content || '',
        continue_reading_after: data.continue_reading_after || 300,
      });
      notify('Chapter generated.');

      setTimeout(() => {
        outputRef.current?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 120);
    } catch (error) {
      const message = error?.response?.data?.message || 'Generation failed. Please try again.';
      notify(message, 'error');
    } finally {
      setIsGenerating(false);
      setTimeout(() => setShowSkeleton(false), 350);
    }
  };

  const saveChapter = async (status = 'draft') => {
    if (!result.title || !result.content) {
      notify('Generate a chapter before saving.', 'error');
      return;
    }

    setIsSaving(true);
    try {
      const { data } = await axios.post('/api/chapters', {
        title: result.title,
        content: result.content,
        mood: form.mood,
        continue_reading_after: result.continue_reading_after,
        status,
      });

      notify(status === 'draft' ? 'Draft saved.' : 'Chapter saved.', 'success');
      if (data.chapter_id) {
        // no-op for now, kept for future deep link
      }
    } catch (error) {
      const message = error?.response?.data?.message || 'Save failed.';
      notify(message, 'error');
    } finally {
      setIsSaving(false);
    }
  };

  const copyContent = async () => {
    if (!result.content) return;
    try {
      await navigator.clipboard.writeText(`Title: ${result.title}\n\n${result.content}`);
      notify('Copied to clipboard.');
    } catch {
      notify('Copy failed.', 'error');
    }
  };

  const shellClass = darkMode
    ? 'min-h-screen bg-slate-950 text-slate-100'
    : 'min-h-screen bg-gradient-to-br from-rose-50 via-violet-50 to-indigo-50 text-slate-800';

  const cardClass = darkMode
    ? 'bg-slate-900/90 border border-slate-700'
    : 'bg-white/85 border border-white';

  return (
    <div className={`${shellClass} transition-colors duration-300`}>
      <Toast toast={toast} />

      <div className="max-w-6xl mx-auto px-4 py-8 md:py-10">
        <div className="flex items-center justify-between mb-6">
          <div>
            <h1 className="text-3xl md:text-4xl font-semibold">AI Chapter Writer</h1>
            <p className="mt-1 text-sm opacity-75">Create emotional, diary-style chapters for Soul Diaries.</p>
          </div>
          <button
            type="button"
            onClick={() => setDarkMode((v) => !v)}
            className="px-4 py-2 rounded-full text-sm font-medium bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900"
          >
            {darkMode ? 'Light Mode' : 'Dark Mode'}
          </button>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-5 gap-6">
          <div className={`lg:col-span-2 rounded-3xl p-5 md:p-6 shadow-xl transition-all ${cardClass}`}>
            <label className="text-sm font-medium block mb-2">Story Prompt</label>
            <textarea
              rows={6}
              value={form.prompt}
              onChange={(e) => setForm((p) => ({ ...p, prompt: e.target.value }))}
              placeholder="A girl receives an old letter from her future self..."
              className="w-full rounded-2xl p-3 text-sm border border-violet-200 bg-white/90 text-slate-800"
            />

            <label className="text-sm font-medium block mt-4 mb-2">Mood</label>
            <select
              value={form.mood}
              onChange={(e) => setForm((p) => ({ ...p, mood: e.target.value }))}
              className="w-full rounded-xl p-3 text-sm border border-violet-200 bg-white/90 text-slate-800"
            >
              {moods.map((m) => (
                <option key={m.value} value={m.value}>{m.label}</option>
              ))}
            </select>

            <label className="text-sm font-medium block mt-4 mb-2">Previous Context (optional)</label>
            <textarea
              rows={4}
              value={form.context}
              onChange={(e) => setForm((p) => ({ ...p, context: e.target.value }))}
              placeholder="She already discovered one truth in the previous chapter..."
              className="w-full rounded-2xl p-3 text-sm border border-violet-200 bg-white/90 text-slate-800"
            />

            <div className="flex flex-wrap gap-2 mt-5">
              <button
                type="button"
                onClick={generateChapter}
                disabled={isGenerating}
                className="px-4 py-2 rounded-xl bg-violet-600 text-white text-sm font-semibold hover:bg-violet-700 disabled:opacity-50"
              >
                {isGenerating ? 'Generating...' : 'Generate Chapter'}
              </button>

              <button
                type="button"
                onClick={generateChapter}
                disabled={isGenerating}
                className="px-4 py-2 rounded-xl bg-fuchsia-100 text-fuchsia-800 text-sm font-semibold hover:bg-fuchsia-200 disabled:opacity-50"
              >
                Regenerate
              </button>

              <button
                type="button"
                onClick={() => saveChapter('published')}
                disabled={isSaving || !result.content}
                className="px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 disabled:opacity-50"
              >
                {isSaving ? 'Saving...' : 'Save'}
              </button>

              <button
                type="button"
                onClick={() => saveChapter('draft')}
                disabled={isSaving || !result.content}
                className="px-4 py-2 rounded-xl border border-slate-300 text-sm font-semibold hover:bg-slate-100 disabled:opacity-50"
              >
                Save Draft
              </button>
            </div>
          </div>

          <div ref={outputRef} className={`lg:col-span-3 rounded-3xl p-5 md:p-6 shadow-xl transition-all ${cardClass}`}>
            <div className="flex items-center justify-between gap-3 mb-4">
              <h2 className="text-xl md:text-2xl font-semibold">Generated Chapter</h2>
              <div className="flex items-center gap-2 text-xs opacity-70">
                <span>{wordCount} words</span>
                <button
                  type="button"
                  onClick={copyContent}
                  className="px-3 py-1 rounded-lg border border-slate-300 hover:bg-slate-100 text-slate-700"
                >
                  Copy
                </button>
              </div>
            </div>

            {showSkeleton && !typedContent ? (
              <div className="space-y-3 animate-pulse">
                <div className="h-8 w-2/3 bg-slate-200 rounded-lg" />
                <div className="h-4 w-full bg-slate-200 rounded" />
                <div className="h-4 w-full bg-slate-200 rounded" />
                <div className="h-4 w-5/6 bg-slate-200 rounded" />
              </div>
            ) : result.content ? (
              <div className="transition-all duration-300">
                <input
                  value={result.title}
                  onChange={(e) => setResult((r) => ({ ...r, title: e.target.value }))}
                  className="w-full bg-transparent text-2xl font-semibold outline-none mb-4"
                />

                <article className="prose prose-slate max-w-none">
                  <p className="leading-8 whitespace-pre-wrap text-[1.05rem]">
                    {teaserText}
                  </p>
                </article>

                {result.content.trim().split(/\s+/).length > Number(result.continue_reading_after || 300) && (
                  <div className="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-900">
                    Continue Reading Break · after {result.continue_reading_after} words
                  </div>
                )}

                <label className="text-sm font-medium block mt-6 mb-2">Edit AI Content</label>
                <textarea
                  rows={14}
                  value={result.content}
                  onChange={(e) => setResult((r) => ({ ...r, content: e.target.value }))}
                  className="w-full rounded-2xl p-3 text-sm border border-violet-200 bg-white/90 text-slate-800"
                />
              </div>
            ) : (
              <div className="text-sm opacity-70 py-20 text-center">
                Your chapter will appear here with a typewriter effect.
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
