# Import the SDK
import boto3
import uuid

# Create s3 client object

# Create a bucket 
print('Creating new bucket with name: {}'.format(bucket_name))

# List all the buckets of the aws account

list_buckets_resp = s3client.list_buckets()
for bucket in list_buckets_resp['Buckets']:
    if bucket['Name'] == bucket_name:
        print('(Just created) --> {} - there since {}'.format(
            bucket['Name'], bucket['CreationDate']))

object_key = 'python_sample_key.txt'

print('Uploading some data to {} with key: {}'.format(
    bucket_name, object_key))
# Put the objectkey object with sample data in S3

# Generate and print the pre-signed URL

try:
    input = raw_input
except NameError:
    pass
input("\nPress enter to continue...")

print('\nNow using Resource API')
# First, create the service resource object

# Now, the bucket object

# Then, the object object

print('Bucket name: {}'.format(bucket.name))
print('Object key: {}'.format(obj.key))
print('Object content length: {}'.format(obj.content_length))
print('Object body: {}'.format(obj.get()['Body'].read()))
print('Object last modified: {}'.format(obj.last_modified))

print('\nDeleting all objects in bucket {}.'.format(bucket_name))
# Delete all objects in the bucket created earlier

print('\nDeleting the bucket.')
# Delete the bucket created earlier
