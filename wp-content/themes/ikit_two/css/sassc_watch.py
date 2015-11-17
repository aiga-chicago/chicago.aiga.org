"""
Simple watcher for sassc compilation since -w watch
feature is not built-in. Using compass or sass is very slow
so libsass should be used. If you are compiling the sass file, you
should install libsass and sassc on your computer, you can then
run this script and it will watch for changes to sass.scss and compile
the stylesheet into stylesheets/sass.css.

https://github.com/sass/libsass
https://github.com/sass/sassc

Usage:

Go to the directory where this file is located and type:
    python sassc_watch.py

"""
import sys
import time
import logging
from watchdog.observers import Observer
from watchdog.events import LoggingEventHandler, FileSystemEventHandler
import subprocess
import time
import os

class CustomEventHandler(FileSystemEventHandler):
    def on_modified(self, event):

        # Compile every file in the sass directory for changes to placeholders or mixins modules
        file_paths = []
        if event.src_path.endswith('mixins.scss') or event.src_path.endswith('placeholders.scss'):
            file_paths = os.listdir(os.path.dirname(event.src_path))
        else:
            # Otherwise just compile the file itself since it doesn't have any mixins that other files may be including
            file_paths.append(os.path.basename(event.src_path))

        for file_path in file_paths:
            file_path = os.path.join(os.path.dirname(event.src_path), file_path)
            if file_path.endswith('.scss'):

                output_file_name = os.path.basename(file_path).replace('.scss', '.css')

                output = subprocess.check_output('sassc %s' % (file_path), shell=True)
                f = open('stylesheets/%s' % (output_file_name),'w')
                print(output)
                print(time.strftime("%H:%M:%S"))
                f.write(output)
                f.close()

if __name__ == "__main__":
    path = 'sass'
    event_handler = CustomEventHandler()
    observer = Observer()
    observer.schedule(event_handler, path, recursive=True)
    observer.start()
    try:
        while True:
            time.sleep(1)
    except KeyboardInterrupt:
        observer.stop()
    observer.join()
