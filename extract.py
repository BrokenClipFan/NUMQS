import io
import re

with io.open('resources/views/driver/map.blade.php', 'r', encoding='utf-8') as f:
    c = f.read()

# Extract HTML
html_match = re.search(r'(<ul class="nav nav-fill" id="queueTabs" role="tablist">.*?</div>\s*</div>)', c, re.DOTALL)
queue_html = html_match.group(1) if html_match else ""

# Replace HTML with @include
if queue_html:
    c = c.replace(queue_html, "@include('driver.partials.queue')")

# Extract JS
js_pattern = r'// \-+\s*// Network polling\s*// \-+\s*function getAllQueues\(\) \{.*?function initClock\(\) \{'
js_match = re.search(js_pattern, c, re.DOTALL)
queue_js = ""
if js_match:
    full_js = js_match.group(0)
    # We want to extract everything except initClock definition
    queue_js = full_js.replace("function initClock() {", "").strip()
    c = c.replace(queue_js, "")

# Also extract the queue DOM elements
dom_pattern = r'const nagaToUlingQueueEl = document\.getElementById\(\'nagaToUlingQueue\'\);\s*const ulingToNagaQueueEl = document\.getElementById\(\'ulingToNagaQueue\'\);\s*const nagaToUlingCountEl = document\.getElementById\(\'nagaToUlingQueueCount\'\);\s*const ulingToNagaCountEl = document\.getElementById\(\'ulingToNagaQueueCount\'\);'
dom_match = re.search(dom_pattern, c)
if dom_match:
    c = c.replace(dom_match.group(0), "")

# We need to wrap the queue_js in a way that it still has access to CURRENT_DRIVER_ID and POLL_INTERVAL_MS.
# Actually, since it's an @include, we can just put a <script> block in the partial, BUT map.blade.php is a module!
# Let's just put the HTML in the partial and keep the JS in map.blade.php for now to avoid breaking scope?
# No, they said "move the Queue into a different page". Let's create a full new page /queue ?
# "move the Queue into a different page cause the file is getting too large"
# If they literally meant a different web page, then creating /queue is what they want.
